# Haiti ITSM Dashboard - Complete Documentation

Sistem manajemen tiket, aset, dan reservasi ruangan dengan notifikasi real-time via WhatsApp.

## 🎯 Fitur Utama

### 1. Dashboard
- Statistik real-time (Total Assets, Active Assets, Total Tickets, Open Tickets)
- Daftar tiket terbaru
- UI modern dengan TailAdmin theme
- Dark mode support

### 2. Manajemen Tiket (Tickets)
- Buat, baca, perbarui, hapus tiket
- Kategorisasi dan prioritas
- Assign ke teknisi
- Lihat history & comments
- Status tracking (OPEN, IN_PROGRESS, RESOLVED, CLOSED)

### 3. Manajemen Aset (Assets)
- Inventory aset IT
- Status tracking (ACTIVE, MAINTENANCE, SOLD, RETIRED)
- Asosiasi dengan tiket

### 4. Reservasi Ruangan (Reservations)
- Booking ruangan meeting dengan tanggal & waktu
- Status approval (PENDING, APPROVED, REJECTED, CANCELLED)
- Otomatis notifikasi saat approved

### 5. Sistem Notifikasi Lengkap
- **In-App Notifications**: Tersimpan di database
- **WhatsApp Notifications**: Real-time SMS via Fonnte.com
- **Notification Bell**: Header indicator dengan badge unread count
- **Full Notification Page**: Kelola semua notifikasi dengan pagination

### 6. User Management
- Profile settings dengan avatar
- Ubah password
- Add phone number untuk WhatsApp notifications
- Role-based access control (Admin, Teknisi, User)

## 📋 API Endpoints

### Authentication
```
POST   /login                  - Login user
POST   /register               - Register akun baru
POST   /logout                 - Logout
GET    /forgot-password        - Request password reset
```

### Dashboard
```
GET    /dashboard              - Main dashboard page
```

### Notifications
```
GET    /api/notifications                    - List semua notifikasi (paginated)
GET    /api/notifications/unread-count      - Hitung notifikasi unread
GET    /api/notifications/latest-unread     - 5 notifikasi terbaru unread
GET    /api/notifications/{id}              - View detail notifikasi
PATCH  /api/notifications/{id}/mark-as-read - Mark notifikasi as read
PATCH  /api/notifications/mark-all-as-read  - Mark semua as read
DELETE /api/notifications/{id}              - Hapus notifikasi
GET    /notifications                       - View notifikasi page (web)
GET    /notifications/{id}                  - View detail notifikasi page (web)
```

### Tickets
```
GET    /tickets                   - List tiket (paginated)
POST   /tickets                   - Buat tiket baru
GET    /tickets/create            - Form create tiket
GET    /tickets/{id}              - View detail tiket
PATCH  /tickets/{id}              - Update tiket
GET    /tickets/{id}/edit         - Form edit tiket
DELETE /tickets/{id}              - Hapus tiket
POST   /tickets/{id}/comments     - Tambah comment
```

## 📦 Struktur Database

### Table: notifications
```
id              integer
user_id         integer (FK)
type            string (ticket_created, ticket_updated, etc)
title           string
message         text
action_type     string (ticket, reservation, asset)
action_id       integer
is_read         boolean
whatsapp_sent   boolean
whatsapp_status string (sent, failed, delivered)
whatsapp_response json
created_at      timestamp
updated_at      timestamp
```

### Relationships
- User → hasMany Notifications
- Notification → belongsTo User

## 🚀 Setup & Installation

### Prerequisites
- PHP 8.1+
- Node.js 16+
- SQLite atau MySQL
- Composer

### 1. Clone & Install

```bash
# Clone repository
git clone <repo-url>
cd haiti-app

# Install dependencies
composer install
npm install

# Generate key
php artisan key:generate
```

### 2. Database Setup

```bash
# Run migrations
php artisan migrate

# (Optional) Seed data
php artisan db:seed
```

### 3. WhatsApp Configuration

Edit `.env`:
```env
WHATSAPP_ENABLED=true
WHATSAPP_FONNTE_URL=https://api.fonnte.com/send
WHATSAPP_FONNTE_KEY=your_fonnte_api_key_here
```

Dapatkan Fonnte API Key:
1. Daftar di https://dashboard.fonnte.com
2. Buka Settings → API Configuration
3. Copy API Key

### 4. Build Assets

```bash
npm run build

# Atau untuk development watch
npm run dev
```

### 5. Start Server

```bash
php artisan serve
# Akses di http://localhost:8000
```

## 📱 WhatsApp Integration (Fonnte.com)

### Sistem Notifikasi Otomatis

Pesan WhatsApp otomatis terkirim saat:

1. **Ticket Dibuat**
   - Penerima: Pembuat ticket
   - Pesan: Detail tiket (kode, judul, kategori, prioritas)

2. **Ticket Diupdate**
   - Penerima: Assignee atau Requester
   - Pesan: Informasi status perubahan

3. **Ticket Diselesaikan**
   - Penerima: Assignee atau Requester
   - Pesan: Konfirmasi penyelesaian

4. **Reservasi Dibuat**
   - Penerima: Pembuat reservasi
   - Pesan: Detail ruangan, tanggal, waktu

5. **Reservasi Disetujui**
   - Penerima: Pembuat reservasi
   - Pesan: Konfirmasi approval

6. **Asset Ditambahkan**
   - Penerima: Admin/Creator
   - Pesan: Detail aset (kode, nama, tipe)

### Format Nomor Telepon

Sistem otomatis support semua format:
- ✅ `62812345678` (format Indonesia)
- ✅ `+62812345678` (dengan country code)
- ✅ `0812345678` (format lokal)

### Testing WhatsApp

```bash
php artisan tinker

$service = app(\App\Services\WhatsAppService::class);
$result = $service->send('62812345678', 'Test message');
dump($result);
```

## 🎨 UI/UX Features

### TailAdmin Theme
- Modern responsive design
- 500+ pre-built components
- Consistent color palette (sky blue brand)
- Professional typography
- Dark/Light mode toggle

### Layout Components
- Fixed sidebar navigation (64px width)
- Sticky header dengan notifications bell
- Responsive grid system
- Proper spacing & typography
- DarkMode toggle di header

### Forms
- Professional input styling
- Real-time validation feedback
- Error messages setiap field
- Proper label & placeholder
- Datetime picker untuk reservasi

### Tables
- Striped rows
- Hover effects
- Responsive scrolling
- Action buttons per row
- Pagination support

### Cards & Modals
- Clean card design
- Shadow & border styling
- Modal confirmation dialogs
- Proper spacing

## 🔐 Security Features

- ✅ CSRF Protection (Laravel tokens)
- ✅ SQL Injection Protection (Eloquent ORM)
- ✅ XSS Prevention (Blade escaping)
- ✅ Password Hashing (bcrypt)
- ✅ Authentication (Laravel Breeze)
- ✅ Authorization (Spatie Permissions)
- ✅ Secure Headers
- ✅ Rate Limiting (built-in)

## 📊 Code Structure

```
app/
├── Models/
│   ├── User.php
│   ├── Ticket.php
│   ├── Asset.php
│   ├── Reservation.php
│   ├── Notification.php
│   └── ...
├── Http/
│   ├── Controllers/
│   │   ├── TicketViewController.php
│   │   ├── NotificationController.php
│   │   ├── NotificationViewController.php
│   │   └── ...
│   └── Requests/ (validation)
├── Services/
│   ├── NotificationService.php
│   └── WhatsAppService.php
└── View/Components/

database/
├── migrations/ (schema definitions)
├── factories/ (test data)
└── seeders/ (initial data)

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php (main layout)
│   │   ├── sidebar.blade.php
│   │   ├── header.blade.php
│   │   └── navigation.blade.php
│   ├── dashboard.blade.php
│   ├── tickets/ (create, edit, show, index)
│   ├── assets/ (create, edit, show, index)
│   ├── reservations/ (create, edit, show, index)
│   ├── notifications/ (index, show)
│   ├── profile/
│   ├── auth/ (login, register)
│   └── components/ (reusable)
├── css/
│   └── app.css
└── js/
    ├── app.js
    └── bootstrap.js

routes/
├── web.php (web routes)
├── api.php (API routes)
├── auth.php (auth routes)
└── console.php

config/
├── app.php
├── database.php
├── services.php (WhatsApp config)
└── ...
```

## 🎓 Usage Examples

### Create Ticket dengan Notifikasi

```php
// app/Http/Controllers/TicketViewController.php
public function store(StoreTicketRequest $request)
{
    $ticket = Ticket::create([
        'code' => (new Ticket())->generateCode(),
        'title' => $request->title,
        'requester_id' => auth()->id(),
        'status' => 'OPEN',
        ...
    ]);

    // Auto-send notification
    $this->notificationService->notifyTicketCreated(
        auth()->user(), 
        $ticket
    );

    return redirect()->route('tickets.show', $ticket);
}
```

### Add Phone Number

```php
// User profile update
$user->update([
    'phone_number' => '+62812345678'
]);

// Next notification akan terkirim ke nomor ini
```

### Query Unread Notifications

```php
// Get 5 unread
$unread = auth()->user()->notifications()
    ->where('is_read', false)
    ->latest()
    ->take(5)
    ->get();

// Count unread
$count = auth()->user()->notifications()
    ->where('is_read', false)
    ->count();
```

## 🐛 Troubleshooting

### WhatsApp Tidak Terkirim

**Checklist:**
1. ✅ `WHATSAPP_ENABLED=true` di .env
2. ✅ `WHATSAPP_FONNTE_KEY` filled dengan benar
3. ✅ User punya phone_number
4. ✅ Nomor format benar: 62xxxxxxxxxx
5. ✅ Check logs: `tail -f storage/logs/laravel.log | grep -i whatsapp`

**Debug:**
```bash
# Lihat notifikasi yang gagal
php artisan tinker
\App\Models\Notification::where('whatsapp_status', 'failed')->get()

# Lihat error message
$notification->whatsapp_response
```

### Notification Tidak Muncul

**Checklist:**
1. Database punya records: `\App\Models\Notification::count()`
2. User ID benar: `auth()->id()`
3. API endpoint accessible: `curl http://localhost:8000/api/notifications`
4. Browser console no errors (F12)

### Assets Build Error

```bash
# Clear cache
npm cache clean --force

# Rebuild
npm install
npm run build
```

### Database Error

```bash
# Reset database
php artisan migrate:fresh

# Re-seed if needed
php artisan db:seed
```

## 📈 Performance

- Notification query di-optimize dengan indexed (user_id, is_read)
- Sidebar bell refresh setiap 30 detik (tidak real-time overload)
- Pagination 15-20 items per page
- Lazy loading relationships

## 🔄 Deployment Checklist

- [ ] Update `.env` dengan production values
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `npm run build` (production assets)
- [ ] Set `APP_DEBUG=false`
- [ ] Setup SSL certificate
- [ ] Configure database backups
- [ ] Setup WhatsApp credentials
- [ ] Test WhatsApp sending
- [ ] Monitor logs

## 📝 License

Proprietary - PT Mitra Teknologi Indonesia

## 👥 Support

Untuk bantuan teknis, hubungi tim support atau lihat file:
- `FONNTE_SETUP.md` - Setup WhatsApp Fonnte
- `NOTIFICATIONS_SETUP.md` - Notification system details
- `.env.setup` - Environment configuration guide
