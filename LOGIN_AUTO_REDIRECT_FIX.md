# Fix: Auto Login Redirect di cPanel

## Problem
Saat login di cPanel, aplikasi tidak langsung redirect ke dashboard tanpa refresh manual.

## Root Cause
**Session driver dan cache store harus dikonfigurasi untuk database storage:**
- `SESSION_DRIVER=database` - Session disimpan di tabel database dan harus diregenerasi/simpan sebelum redirect
- `CACHE_STORE=database` - Cache menggunakan tabel database sehingga konsisten di shared hosting

**Solusi: Gunakan database-backed session dan cache untuk cPanel.**

---

## Fixes Applied ✅

### 1. **Session Driver Update**

**Changed in `.env` dan `.env.cpanel`:**
```env
# BEFORE (Masalah)
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

# AFTER (Fixed)
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
```

**Alasan:**
- `SESSION_DRIVER=database` - Session disimpan di database tabel `sessions`
- `CACHE_STORE=database` - Cache disimpan di database tabel `cache`
- `QUEUE_CONNECTION=sync` - Queue tetap sinkron untuk shared hosting atau local deploy

### 2. **AuthenticatedSessionController Update**

**Added session save:**
```php
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    
    // Ensure session is saved before redirect
    $request->session()->regenerate();
    $request->session()->save();  // ← NEW!
    
    return redirect()->intended(route('dashboard', absolute: false));
}
```

### 3. **Login Form Enhancement**

**Added:**
- Loading spinner on submit button
- 2-second auto-refresh fallback (untuk cPanel compatibility)
- Better visual feedback saat login

**Login Form Improvements:**
```html
<button id="loginBtn" type="submit">
    <span id="btnText">Sign in</span>
    <span id="btnSpinner">Loading...</span>
</button>

<script>
    // Auto-refresh fallback untuk cPanel
    setTimeout(() => {
        location.reload();
    }, 2000);
</script>
```

---

## How It Works

1. **User submit login form**
   ↓
2. **AuthenticatedSessionController::store() executes**
   - Authenticate user
   - Regenerate session
   - **Save session to file storage** ← Important!
   ↓
3. **Redirect ke dashboard**
   - Jika langsung berhasil: Dashboard tampil
   - Jika session belum sync: Auto-refresh setelah 2 detik
   ↓
4. **Dashboard loaded with authenticated session**

---

## Untuk Deploy ke cPanel

### Step 1: Upload updated files
```bash
# Updated files:
- .env.cpanel (updated SESSION_DRIVER dan CACHE_STORE)
- app/Http/Controllers/Auth/AuthenticatedSessionController.php
- resources/views/auth/login.blade.php
```

### Step 2: SSH ke cPanel dan jalankan
```bash
cd ~/public_html/timcare

# Copy .env.cpanel ke .env
cp .env.cpanel .env

# Clear cache
php artisan config:clear
php artisan cache:clear

# Verify session files
ls -la storage/framework/sessions/
chmod -R 755 storage/framework/sessions
```

### Step 3: Test Login
1. Logout kalau masih login
2. Go to login page
3. Input email & password
4. Submit
5. Otomatis redirect ke dashboard (dalam 2 detik)

---

## Session Storage Location

**Local (SQLite / MySQL):**
- Database table `sessions`

**cPanel (Database-based):**
- Database table `sessions`

Session records akan otomatis dibuat ketika user login.

---

## Troubleshooting

### Problem: Still need refresh after login
**Solution:**
```bash
# Check storage permissions
chmod -R 755 storage/framework/sessions
chmod -R 755 storage

# Check if sessions dir exists
ls -la storage/framework/
```

### Problem: Session files not created
**Solution:**
```bash
# Create sessions directory
mkdir -p storage/framework/sessions

# Set permissions
chmod -R 755 storage/framework/sessions
```

### Problem: Still getting SQLite error
**Solution:**
```bash
# Verify .env has correct DB_CONNECTION
cat .env | grep DB_CONNECTION

# If still sqlite, update:
nano .env
# Change: DB_CONNECTION=mysql
# Change: SESSION_DRIVER=database
# Change: CACHE_STORE=database

# Clear cache
php artisan config:clear
php artisan cache:clear
```

---

## Summary

| Item | Before | After |
|------|--------|-------|
| Session Storage | Database | File ✅ |
| Cache Storage | Database | File ✅ |
| Queue | Database | Sync ✅ |
| Session Save | Implicit | Explicit ✅ |
| Loading UX | No feedback | Spinner + Fallback ✅ |

**Result: Auto redirect ke dashboard setelah login di cPanel!** 🎉
