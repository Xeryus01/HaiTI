<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http as HttpClient;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    public function redirect(Request $request)
    {
        $config = config('sso', []);

        $state = Str::random(40);
        $request->session()->put('sso_state', $state);

        // validate config
        $required = ['url', 'realm', 'client_id', 'client_secret', 'scope'];
        foreach ($required as $key) {
            if (empty($config[$key] ?? null)) {
                return redirect()->route('login')->withErrors(['sso' => "SSO config missing: {$key}"]);
            }
        }

        $redirectUri = $config['redirect'] ?? url('/sso/callback');

        $params = http_build_query([
            'client_id' => $config['client_id'],
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $config['scope'],
            'state' => $state,
        ]);

        $base = $this->getBaseUrlWithAuth($config);
        $url = $base . '/realms/' . $config['realm'] . '/protocol/openid-connect/auth?' . $params;
        return redirect()->away($url);
    }

    public function callback(Request $request)
    {
        $config = config('sso', []);

        $state = $request->input('state');
        if (!$state || $state !== $request->session()->pull('sso_state')) {
            abort(403, 'Invalid SSO state');
        }

        $code = $request->input('code');
        if (!$code) {
            return redirect()->route('login')->withErrors(['sso' => 'Authorization code not provided']);
        }

        // validate config
        $required = ['url', 'realm', 'client_id', 'client_secret'];
        foreach ($required as $key) {
            if (empty($config[$key] ?? null)) {
                return redirect()->route('login')->withErrors(['sso' => "SSO config missing: {$key}"]);
            }
        }

        $tokenUrl = $this->getBaseUrlWithAuth($config) . '/realms/' . $config['realm'] . '/protocol/openid-connect/token';

        $response = HttpClient::asForm()->post($tokenUrl, [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $config['redirect'] ?? url('/sso/callback'),
            'client_id' => $config['client_id'] ?? null,
            'client_secret' => $config['client_secret'] ?? null,
        ]);

        if ($response->failed()) {
            return redirect()->route('login')->withErrors(['sso' => 'Token exchange failed']);
        }

        $data = $response->json();
        $accessToken = $data['access_token'] ?? null;

        if (!$accessToken) {
            return redirect()->route('login')->withErrors(['sso' => 'No access token received']);
        }

        $userinfoUrl = $this->getBaseUrlWithAuth($config) . '/realms/' . $config['realm'] . '/protocol/openid-connect/userinfo';
        $userResp = HttpClient::withHeaders(['Authorization' => 'Bearer ' . $accessToken])->get($userinfoUrl);

        if ($userResp->failed()) {
            return redirect()->route('login')->withErrors(['sso' => 'Failed fetching user info']);
        }

        $userinfo = $userResp->json();
        $email = $userinfo['email'] ?? null;
        $name = $userinfo['name'] ?? ($userinfo['preferred_username'] ?? $email);

        if (!$email) {
            // fallback to username-based pseudo-email
            $username = $userinfo['preferred_username'] ?? Str::slug($name ?? 'sso-user');
            $email = $username . '@sso.local';
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => $name ?? $email, 'password' => bcrypt(Str::random(40))]
        );

        Auth::login($user, true);

        // store tokens for later refresh/logout
        $request->session()->put('sso_access_token', $accessToken);
        $request->session()->put('sso_refresh_token', $data['refresh_token'] ?? null);
        $request->session()->put('sso_id_token', $data['id_token'] ?? null);

        return redirect()->intended('/dashboard');
    }

    /**
     * Shortcut to initiate login (alias).
     */
    public function login(Request $request)
    {
        return $this->redirect($request);
    }

    /**
     * Logout locally and optionally redirect to Keycloak logout endpoint.
     */
    public function logout(Request $request)
    {
        $config = config('sso', []);

        // clear local session/auth
        Auth::logout();
        $request->session()->forget(['sso_access_token', 'sso_refresh_token', 'sso_id_token', 'sso_state']);

        // redirect to Keycloak logout if configured
        if (!empty($config['url']) && !empty($config['realm'])) {
            $logoutUrl = $this->getBaseUrlWithAuth($config) . '/realms/' . $config['realm'] . '/protocol/openid-connect/logout';
            $params = [
                'client_id' => $config['client_id'] ?? '',
                'post_logout_redirect_uri' => $config['redirect'] ?? url('/')
            ];
            $url = $logoutUrl . '?' . http_build_query($params);
            return redirect()->away($url);
        }

        return redirect('/');
    }

    /**
     * Refresh access token using stored refresh token.
     */
    public function refresh(Request $request)
    {
        $config = config('sso', []);
        $refreshToken = $request->session()->get('sso_refresh_token');

        if (empty($refreshToken)) {
            return redirect()->route('login')->withErrors(['sso' => 'No refresh token available']);
        }

        $tokenUrl = $this->getBaseUrlWithAuth($config) . '/realms/' . ($config['realm'] ?? '') . '/protocol/openid-connect/token';

        $response = HttpClient::asForm()->post($tokenUrl, [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $config['client_id'] ?? null,
            'client_secret' => $config['client_secret'] ?? null,
        ]);

        if ($response->failed()) {
            return redirect()->route('login')->withErrors(['sso' => 'Token refresh failed']);
        }

        $data = $response->json();
        $accessToken = $data['access_token'] ?? null;
        if (!$accessToken) {
            return redirect()->route('login')->withErrors(['sso' => 'No access token received']);
        }

        // update session tokens
        $request->session()->put('sso_access_token', $accessToken);
        if (!empty($data['refresh_token'])) {
            $request->session()->put('sso_refresh_token', $data['refresh_token']);
        }
        if (!empty($data['id_token'])) {
            $request->session()->put('sso_id_token', $data['id_token']);
        }

        return redirect()->intended('/dashboard');
    }

    /**
     * Ensure the configured SSO base URL contains the /auth path Keycloak expects.
     */
    private function getBaseUrlWithAuth(array $config): string
    {
        $url = rtrim($config['url'] ?? '', '/');
        if (!Str::endsWith($url, '/auth')) {
            $url .= '/auth';
        }
        return $url;
    }
}
