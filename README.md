# Helpdesk Ticketing System

Aplikasi Helpdesk Ticketing System berbasis Laravel untuk mengelola laporan masalah dari user kepada IT Support. Aplikasi ini memiliki dua role utama:

- `user`: membuat tiket, melihat tiket miliknya sendiri, dan memantau status tiket.
- `it`: melihat semua tiket, memfilter tiket, membuka detail tiket, dan mengubah status tiket sampai selesai.

## Fitur Utama

- Authentication: register, login, logout.
- Role authorization untuk membedakan akses user dan IT Support.
- User dapat membuat tiket berdasarkan kategori masalah.
- IT Support dapat melihat semua tiket dan memfilter berdasarkan status, kategori, atau tanggal.
- Status tiket berjalan sesuai flow:

```text
Open -> On Progress -> Resolved -> Closed
```

- Setiap perubahan status disimpan ke `ticket_logs` sebagai histori tiket.

## Requirement

Pastikan komputer sudah memiliki:

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan npm
- MySQL

Project ini menggunakan Laravel 11 dan secara default menggunakan MySQL sesuai konfigurasi `.env.example`.

## Cara Instalasi

1. Clone atau ekstrak project.

```bash
git clone https://github.com/audreyvania/TicketingHeldesk.git
cd TicketingHeldesk
```

2. Install dependency PHP.

```bash
composer install
```

3. Install dependency JavaScript.

```bash
npm install
```

4. Copy file environment.

```bash
cp .env.example .env
```

Untuk Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

5. Generate application key.

```bash
php artisan key:generate
```

6. Jalankan migration dan seeder.

```bash
php artisan migrate --seed
```

Perintah ini akan membuat tabel dan mengisi data awal, termasuk kategori tiket, akun IT Support, dan satu akun user contoh.

7. Build asset frontend.

```bash
npm run build
```

8. Jalankan server Laravel.

```bash
php artisan serve
```

Aplikasi dapat dibuka di:

```text
http://127.0.0.1:8000
```

## Akun Demo

Akun IT Support:

```text
Email: it@helpdesk.com
Password: itpass123
Role: it
```

User biasa dapat dibuat melalui halaman register. User yang register otomatis mendapat role `user`.

Atau gunakan akun user contoh dari seeder:

```text
Email: test@example.com
Password: password
Role: user
```

## Alur Penggunaan

### User

1. Register atau login sebagai user biasa.
2. Masuk ke dashboard user.
3. Klik Create Ticket.
4. Isi title, category, dan description.
5. Submit tiket.
6. Tiket baru otomatis memiliki status `Open`.
7. User dapat melihat progress tiket dan histori update dari IT Support.

### IT Support

1. Login menggunakan akun IT Support.
2. Sistem mengarahkan IT ke dashboard admin.
3. IT melihat ringkasan jumlah tiket berdasarkan status.
4. IT membuka menu Manage All Tickets untuk melihat semua tiket.
5. IT dapat memfilter tiket berdasarkan status, kategori, dan tanggal.
6. IT membuka detail tiket.
7. IT mengubah status tiket sesuai flow.
8. Setiap update status wajib memiliki note.
9. Sistem menyimpan perubahan status ke tabel `tickets` dan menyimpan histori ke `ticket_logs`.

## Struktur Database Utama

### `users`

Menyimpan data akun pengguna.

- `id`
- `name`
- `email`
- `email_verified_at`
- `password`
- `remember_token`
- `role`
- `created_at`
- `updated_at`

### `categories`

Menyimpan kategori masalah.

- `id`
- `name`
- `created_at`
- `updated_at`

### `tickets`

Menyimpan data utama tiket.

- `id`
- `ticket_no`
- `user_id`
- `category_id`
- `title`
- `description`
- `status`
- `created_at`
- `updated_at`

### `ticket_logs`

Menyimpan histori tiket.

- `id`
- `ticket_id`
- `user_id`
- `status`
- `note`
- `created_at`
- `updated_at`

## File Penting

- `routes/web.php`: daftar route aplikasi.
- `app/Http/Controllers/TicketController.php`: logic dashboard, create ticket, list ticket, detail ticket, filter tiket, dan update status.
- `app/Http/Middleware/RoleMiddleware.php`: pengecekan role user.
- `app/Models/Ticket.php`: model tiket dan relasi ke user, category, logs, dan latestLog.
- `app/Models/TicketLog.php`: model histori tiket.
- `app/Models/Category.php`: model kategori masalah.
- `database/migrations`: struktur tabel database.
- `database/seeders`: data awal aplikasi.
- `resources/views/admin/dashboard.blade.php`: dashboard IT Support.
- `resources/views/tickets`: halaman ticketing.
