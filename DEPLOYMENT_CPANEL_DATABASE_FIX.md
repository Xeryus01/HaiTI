# 🚀 DEPLOYMENT GUIDE - Cara Fix Database Error di cPanel

## ⚠️ MASALAH:
Error menunjukkan aplikasi **masih menggunakan SQLite** padahal sudah di cPanel (seharusnya MySQL).

```
Database file at path [C:\Users\BPS 1900\Documents\HaiTI\database\database.sqlite] does not exist
Connection: sqlite
```

## ✅ SOLUSI:

### **Langkah 1: Verifikasi `.env` di cPanel**

**VIA SSH cPanel:**
```bash
ssh username@your-cpanel-domain.com
cd ~/public_html/timcare  # atau path aplikasi Anda
cat .env | grep DB_
```

**Output yang BENAR seharusnya:**
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=digistat_timcare
DB_USERNAME=digistat_bps1900
DB_PASSWORD=bps19jaya
```

**Jika masih SQLite:**
```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### **Langkah 2: Update `.env` di cPanel ke MySQL**

**Option A: Via SSH (Recommended)**
```bash
# Masuk ke folder aplikasi
cd ~/public_html/timcare

# Edit .env
nano .env
```

Ubah database section menjadi:
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=digistat_timcare
DB_USERNAME=digistat_bps1900
DB_PASSWORD=bps19jaya
```

Save: `Ctrl+O` → Enter → `Ctrl+X`

**Option B: Via cPanel File Manager**
1. Login ke cPanel
2. File Manager → Navigate ke folder aplikasi
3. Edit `.env` → Ubah `DB_CONNECTION` dari `sqlite` ke `mysql`
4. Simpan

### **Langkah 3: Clear Config Cache**

**SANGAT PENTING! Cache lama masih tersimpan.**

```bash
cd ~/public_html/timcare
php artisan config:clear
php artisan cache:clear
```

Output:
```
Configuration cache cleared successfully.
Application cache cleared successfully.
```

### **Langkah 4: Verify Database Connection**

```bash
php artisan tinker
```

Ketik:
```php
>>> DB::connection()->getPdo()
>>> DB::select('SELECT 1')
>>> exit
```

Jika tidak error = Database connected! ✅

### **Langkah 5: Run Migrations**

```bash
php artisan migrate --force
php artisan db:seed --force
```

### **Langkah 6: Cache untuk Production (Opsional tapi Recommended)**

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔍 DEBUGGING CHECKLIST:

**Jika masih error:**

1. **SSH ke cPanel dan cek file `.env`:**
   ```bash
   cat .env | head -30
   ```
   Pastikan `DB_CONNECTION=mysql` (bukan sqlite)

2. **Hapus config cache di server:**
   ```bash
   rm bootstrap/cache/config.php
   rm bootstrap/cache/services.php
   rm bootstrap/cache/packages.php
   php artisan config:clear
   ```

3. **Cek permissions:**
   ```bash
   chmod -R 755 bootstrap/cache
   chmod -R 755 storage
   ```

4. **Cek log error:**
   ```bash
   tail -50 storage/logs/laravel.log
   ```

5. **Test MySQL koneksi dari cPanel:**
   ```bash
   php -r "
   \$conn = new mysqli('localhost', 'digistat_bps1900', 'bps19jaya', 'digistat_timcare');
   if (\$conn->connect_error) {
       echo 'MySQL Error: ' . \$conn->connect_error;
   } else {
       echo 'MySQL Connected!';
   }
   "
   ```

---

## 📋 SCRIPT OTOMATIS (Copy & Paste ke SSH):

**Jika ingin semua sekaligus:**

```bash
#!/bin/bash
cd ~/public_html/timcare

echo "🔄 Clearing caches..."
php artisan config:clear
php artisan cache:clear

echo "🔍 Verifying database connection..."
php artisan tinker <<EOF
DB::connection()->getPdo();
echo "✅ Database connected!\n";
exit;
EOF

echo "🗄️ Running migrations..."
php artisan migrate --force

echo "🌱 Seeding database..."
php artisan db:seed --force

echo "⚡ Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment selesai!"
```

---

## 🎯 RINGKASAN:

| Step | Command | Purpose |
|------|---------|---------|
| 1 | `cat .env \| grep DB_` | Verify `.env` is MySQL |
| 2 | `nano .env` | Update `.env` if needed |
| 3 | `php artisan config:clear` | **CRITICAL**: Clear config cache |
| 4 | `php artisan tinker` | Test connection |
| 5 | `php artisan migrate --force` | Create tables |
| 6 | `php artisan config:cache` | Cache for production |

---

## ✨ CATATAN:

- **Local (Anda)**: Gunakan `.env` dengan SQLite ✅
- **cPanel**: HARUS pakai `.env` dengan MySQL ⚠️
- **Config Cache**: Clear setiap kali update `.env` 🔄
- **Logs**: Check `storage/logs/laravel.log` jika ada error 📝

Apakah Anda sudah SSH ke cPanel dan update `.env`? Jika sudah, lanjutkan Langkah 3 di atas.
