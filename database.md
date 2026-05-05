# Skema Database Helpdesk Ticketing System

Dokumentasi ini menjelaskan skema database utama aplikasi Helpdesk Ticketing System.

## Tabel Utama

### `users`
Menyimpan data akun pengguna.

Kolom:
- `id` (bigint, **PRIMARY KEY**)
- `name` (string)
- `email` (string, unique)
- `email_verified_at` (timestamp, nullable)
- `password` (string)
- `remember_token` (string, nullable)
- `role` (string, default `user`)
- `created_at` / `updated_at` (timestamps)

Keterangan:
- `id` adalah **PRIMARY KEY**.
- `role` membedakan akses antara `user` dan `it`.
- `email` harus unik untuk setiap akun.

### `categories`
Menyimpan kategori masalah tiket.

Kolom:
- `id` (bigint, **PRIMARY KEY**)
- `name` (string)
- `created_at` / `updated_at` (timestamps)

Keterangan:
- `id` adalah **PRIMARY KEY**.
- `categories` digunakan untuk memilih kategori tiket.

### `tickets`
Menyimpan data tiket yang dibuat oleh user.

Kolom:
- `id` (bigint, **PRIMARY KEY**)
- `ticket_no` (string, unique)
- `user_id` (foreign key ke `users.id`)
- `category_id` (foreign key ke `categories.id`)
- `title` (string)
- `description` (text)
- `status` (enum: `Open`, `On Progress`, `Resolved`, `Closed`, default `Open`)
- `created_at` / `updated_at` (timestamps)

Keterangan:
- `id` adalah **PRIMARY KEY**.
- `user_id` adalah **FOREIGN KEY** yang mengacu ke `users.id`.
- `category_id` adalah **FOREIGN KEY** yang mengacu ke `categories.id`.
- `ticket_no` harus unik untuk setiap tiket.
- `status` mengikuti alur tiket: `Open -> On Progress -> Resolved -> Closed`.

### `ticket_logs`
Menyimpan histori perubahan status dan catatan tiket.

Kolom:
- `id` (bigint, **PRIMARY KEY**)
- `ticket_id` (foreign key ke `tickets.id`)
- `user_id` (foreign key ke `users.id`)
- `status` (string)
- `note` (text, nullable)
- `created_at` / `updated_at` (timestamps)

Keterangan:
- `id` adalah **PRIMARY KEY**.
- `ticket_id` adalah **FOREIGN KEY** ke `tickets.id`.
- `user_id` adalah **FOREIGN KEY** ke `users.id`.
- `note` dapat diisi dengan keterangan perubahan status.

## Relasi Antar Tabel

- `users.id` -> `tickets.user_id` (**1 to many**)
- `categories.id` -> `tickets.category_id` (**1 to many**)
- `tickets.id` -> `ticket_logs.ticket_id` (**1 to many**)
- `users.id` -> `ticket_logs.user_id` (**1 to many**)

## Highlight Kunci Penting

- PRIMARY KEY:
  - `users.id`
  - `categories.id`
  - `tickets.id`
  - `ticket_logs.id`
- FOREIGN KEY:
  - `tickets.user_id` -> `users.id`
  - `tickets.category_id` -> `categories.id`
  - `ticket_logs.ticket_id` -> `tickets.id`
  - `ticket_logs.user_id` -> `users.id`

Keterangan:
- **PRIMARY KEY** adalah kolom unik yang menjadi identitas setiap baris di tabel.
- **FOREIGN KEY** adalah kolom di tabel anak yang mengacu ke PRIMARY KEY di tabel lain untuk membentuk relasi.

## Catatan Tambahan

Terdapat juga tabel bawaan Laravel untuk autentikasi/sesi (`password_reset_tokens`, `sessions`), tetapi fokus utama aplikasi ini adalah tabel `users`, `categories`, `tickets`, dan `ticket_logs`.
