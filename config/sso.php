<?php

return [
    // Base Keycloak URL (no trailing slash), e.g. https://auth.example.com/auth
    'url' => env('SSO_URL', ''),

    // Realm name
    'realm' => env('SSO_REALM', ''),

    // Client credentials
    'client_id' => env('SSO_CLIENT_ID', ''),
    'client_secret' => env('SSO_CLIENT_SECRET', ''),

    // Redirect/callback URL to use after authentication
    'redirect' => env('SSO_REDIRECT', 'https://digistat.web.bps.go.id/timcare/sso/callback'),

    // Scopes
    'scope' => env('SSO_SCOPE', 'openid profile email'),
];
