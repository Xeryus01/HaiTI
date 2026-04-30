# TimCare ITSM Dashboard

Sistem Manajemen Layanan TI (IT Service Management) berbasis web untuk membantu organisasi mengelola tiket support, inventaris aset, dan reservasi ruang rapat.

## 🚀 Fitur Utama

- **Manajemen Tiket Support** - Sistem tiket dengan kategori, prioritas, dan status tracking
- **Inventaris Aset** - Pelacakan perangkat keras dan lunak
- **Reservasi Ruang Zoom** - Sistem booking ruang meeting online
- **Notifikasi Real-time** - Email dan WhatsApp notifications
- **Role-based Access Control** - Sistem izin berbasis peran
- **Dashboard Analytics** - Laporan dan statistik real-time
- **Mobile Responsive** - Antarmuka yang responsif di semua device

## 🛠️ Tech Stack

- **Backend**: Laravel 10.x
- **Frontend**: Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **File Storage**: Local/Public disk
- **Queue**: Database driver
- **Cache**: File cache (optimized for cPanel)

## 📋 Persyaratan Sistem

- PHP 8.1 atau lebih tinggi
- MySQL 5.7+ atau MariaDB 10.0+
- Composer
- Node.js & NPM (untuk development)
- cPanel hosting (recommended)

## 🚀 Quick Start (Development)

```bash
# Clone repository
git clone <repository-url>
cd timcare

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
# Edit .env dengan database credentials
php artisan migrate --seed

# Build assets
npm run build

# Start development server
php artisan serve
```

## 🌐 Deployment ke cPanel

### Struktur Folder yang Benar

**PENTING:** Untuk keamanan cPanel, aplikasi Laravel HARUS dipisah dari public_html:

```
/home/username/
├── public_html/          # Hanya folder public Laravel
│   ├── index.php
│   ├── .htaccess
│   ├── assets/
│   └── storage/          # Symlink ke ../timcare/storage/app/public
├── timcare/              # Folder aplikasi utama (di luar public_html)
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── artisan
│   ├── composer.json
│   ├── .env
│   └── ...
```

### Persiapan di cPanel

1. **Buat Database MySQL**
   - Login cPanel → MySQL Databases
   - Buat database baru (contoh: `timcare_db`)
   - Buat user database (contoh: `timcare_user`)
   - Berikan full privileges

2. **Upload Files**
   - Upload seluruh project ke folder temporary
   - JANGAN upload langsung ke public_html

3. **Setup Struktur Folder**
   ```bash
   # Dari folder project yang diupload
   bash setup-cpanel-structure.sh
   ```

### Setup Otomatis

```bash
# Jalankan deployment script
cd ~/timcare
bash deploy.sh
```

Atau manual:
```bash
cd ~/timcare
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force

# Buat storage symlink dari public_html
cd ~/public_html
ln -s ../timcare/storage/app/public storage

cd ~/timcare
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod -R 755 storage bootstrap/cache
```

### Konfigurasi Mail (SMTP cPanel)

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=admin@yourdomain.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

## 📚 Dokumentasi

- [Panduan Deployment cPanel](DEPLOYMENT_CPANEL.md)
- [Setup Cron Jobs](CRON_SETUP.md)
- [API Documentation](docs/API.md)
- [Database ERD](docs/ERD.md)

## 🔧 Konfigurasi Production

### Environment Variables (.env)

```env
APP_NAME="TimCare ITSM Dashboard"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=timcare_db
DB_USERNAME=timcare_user
DB_PASSWORD=your_password

# Cache & Performance
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=sync
```

### Cron Jobs (cPanel)

```bash
# Queue worker (jika menggunakan queue)
* * * * * cd /home/username/public_html/timcare && php artisan queue:work --sleep=3 --tries=3

# Daily maintenance
0 2 * * * cd /home/username/public_html/timcare && php artisan cache:clear
```

## 🔒 Security

- APP_DEBUG=false di production
- File permissions: 755 (folders), 644 (files)
- .env tidak boleh accessible via web
- SSL certificate wajib
- Regular database backup

## 📊 Backup & Maintenance

```bash
# Database backup
bash backup.sh

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 🤝 Support

Untuk support dan pertanyaan:
- Cek logs di `storage/logs/`
- Monitor cPanel error logs
- Pastikan PHP version kompatibel

## 📄 License

This project is proprietary software.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

# ITSM Module (Tickets + Assets + Dashboard)

This project has been prepared with starter code for an ITSM dashboard:

## What was added

- API routes: `routes/api.php` (protected by `auth:sanctum`)
- Models: `Asset`, `Ticket`, `TicketComment`, `Reservation`, `Attachment`, `Log`

Ticket statuses have been extended to include `ASSIGNED_DETECT`, `SOLVED_WITH_NOTES`, and `SOLVED` alongside `OPEN`.- Migrations for tables: `assets`, `tickets`, `ticket_comments`, `reservations`, `attachments`, `logs`
- API Controllers:
  - `GET /api/dashboard/summary`
  - `GET /api/tickets`
  - `POST /api/tickets`
  - `GET /api/tickets/{ticket}`
  - `POST /api/tickets/{ticket}/status`
  - `POST /api/tickets/{ticket}/comments`
- RBAC roles seeder: `Database\Seeders\RoleSeeder` (Admin, Teknisi, User)

## Setup steps

1) Install dependencies:

```bash
composer install
composer require laravel/sanctum spatie/laravel-permission
```

2) Publish package migrations & configs, then migrate:

```bash
php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
php artisan vendor:publish --provider="Spatie\\Permission\\PermissionServiceProvider"
php artisan migrate
```

3) Seed roles:

```bash
php artisan db:seed
```

4) Ensure routing loads API routes (already updated in `bootstrap/app.php`).

