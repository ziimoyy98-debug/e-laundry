# 🧺 E-Londri — Sistem Manajemen & Kasir Laundry Digital

![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)

Aplikasi manajemen operasional dan sistem kasir *laundry* digital berbasis RESTful API. Proyek ini dirancang untuk mempermudah pencatatan transaksi kasir, pelacakan status pengerjaan cuci/setrika secara *real-time*, serta pengelolaan data pelanggan dan paket layanan.

---

## 📋 Daftar Isi

- [Struktur Tim & Pembagian Peran](#-struktur-tim--pembagian-peran)
- [Arsitektur & Teknologi](#-arsitektur--teknologi)
- [Struktur Basis Data & ERD](#-struktur-basis-data--erd)
- [Panduan Instalasi & Konfigurasi Lokal](#-panduan-instalasi--konfigurasi-lokal)
- [Dokumentasi RESTful API](#-dokumentasi-restful-api)
- [Pengujian (Testing)](#-pengujian-testing)
- [Lisensi](#-lisensi)

---

## 👥 Struktur Tim & Pembagian Peran

| Peran | Anggota Tim | Tanggung Jawab Utama |
| :--- | :--- | :--- |
| **Project Manager** | Rizki Qadri | Pengendalian *timeline*, penyusunan papan Kanban, QA fungsional, dan finalisasi dokumentasi repositori. |
| **Database Analyst** | Rasya Anggara Wijaya | Perancangan ERD, skema migrasi Laravel, penentuan relasi Eloquent, serta pembuatan *Seeder* & *Factory*. |
| **Backend Developer** | Finsa Ananda Srg | Pembangunan RESTful API, validasi request (`FormRequest`), API Resource, dan pengelolaan transaksi database. |
| **Frontend Developer** | M Alfakhridzi | Pengembangan antarmuka kasir, konsumsi API *endpoint*, validasi *client-side*, dan penanganan *error flow*. |

---

## 🛠️ Arsitektur & Teknologi

- **Framework Backend:** Laravel 12
- **Database Management System:** MySQL 8.0
- **Authentication:** Laravel Sanctum / JWT
- **API Documentation & Testing:** Postman Collection v2.1
- **Version Control System:** Git & GitHub

---

## 🗄️ Struktur Basis Data & ERD

Sistem E-Londri menggunakan 4 tabel utama dengan relasi relasional:

```text
[ customers ] (1) <--- (N) [ orders ] (N) <---> (N) [ services ]
                             │
                     (via order_details)
```

### Penjelasan Entitas:

1. **`customers`** (Master Pelanggan): Menyimpan data identitas pelanggan (`name`, `phone`, `address`).
2. **`services`** (Master Layanan): Menyimpan paket laundry (`name`, `price_per_kg`, `unit`).
3. **`orders`** (Kepala Transaksi): Mencatat nota transaksi (`invoice_code`, `order_date`, `completion_date`, `status`, `total_price`).
4. **`order_details`** (Pivot Table): Hubungan *Many-to-Many* antara pesanan dan paket layanan (`qty`, `subtotal`).

---

## ⚙️ Panduan Instalasi & Konfigurasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan lokal:

### 1. Prasyarat Sistem

- PHP >= 8.2
- Composer >= 2.x
- MySQL Server
- Git

### 2. Langkah Instalasi

```bash
# 1. Clone repositori dari GitHub
git clone https://github.com/ziimoyy98-debug/e-laundry.git

# 2. Masuk ke direktori proyek
cd elondri-pengayaan

# 3. Install dependensi PHP via Composer
composer install

# 4. Salin berkas lingkungan (.env)
cp .env.example .env

# 5. Generate Application Key
php artisan key:generate
```

### 3. Konfigurasi Basis Data (`.env`)

Buka berkas `.env` dan atur koneksi database lokal Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_elondri
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seeding Data Dummy

Jalankan migrasi tabel beserta seeder untuk menggenerasi data awal master & transaksi:

```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Peladen Lokal

```bash
php artisan serve
```

Aplikasi API akan berjalan pada alamat: `http://127.0.0.1:8000`

---

## 🔗 Dokumentasi RESTful API

### Endpoint Utama

| Method | Endpoint | Deskripsi | Access |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/orders` | Menampilkan seluruh daftar transaksi | Public/Kasir |
| `POST` | `/api/orders` | Membuat transaksi laundry baru | Kasir |
| `GET` | `/api/orders/{id}` | Menampilkan detail transaksi berdasarkan ID | Public/Kasir |
| `PATCH` | `/api/orders/{id}/status` | Memperbarui status laundry (`pending` → `completed`) | Kasir/Admin |

### Contoh Payload Request (`POST /api/orders`)

```json
{
  "customer_id": 1,
  "completion_date": "2026-09-16",
  "services": [
    {
      "service_id": 1,
      "qty": 3
    },
    {
      "service_id": 2,
      "qty": 1
    }
  ]
}
```

### Contoh Response JSON Success (`201 Created`)

```json
{
  "status": true,
  "message": "Transaksi laundry berhasil dibuat",
  "data": {
    "id": 12,
    "invoice_code": "INV-20260914-482",
    "order_date": "2026-09-14 08:30:00",
    "completion_date": "2026-09-16",
    "status": "pending",
    "total_price": 45000,
    "customer": {
      "id": 1,
      "name": "Budi Santoso",
      "phone": "08123456789"
    },
    "details": [
      {
        "service_id": 1,
        "service_name": "Cuci Kiloan Regular",
        "price_per_kg": 10000,
        "qty": 3,
        "subtotal": 30000
      },
      {
        "service_id": 2,
        "service_name": "Setrika Express",
        "price_per_kg": 15000,
        "qty": 1,
        "subtotal": 15000
      }
    ]
  }
}
```

### Contoh Response Error Validation (`422 Unprocessable Entity`)

```json
{
  "message": "Pelanggan wajib dipilih. (and 1 more error)",
  "errors": {
    "customer_id": [
      "Pelanggan wajib dipilih."
    ],
    "services": [
      "Minimal pilih 1 layanan laundry."
    ]
  }
}
```

---

## 🧪 Pengujian (Testing)

Pengujian API dilakukan menggunakan **Postman**. Berkas collection pengujian dapat diunduh pada direktori proyek:
📁 `docs/E-Londri_API.postman_collection.json`

Skenario pengujian mencakup:
1. **200 OK** — Fetch data daftar transaksi & detail order.
2. **201 Created** — Berhasil menyimpan transaksi baru dengan kalkulasi harga otomatis.
3. **422 Unprocessable Entity** — Validasi error saat input payload tidak lengkap.
4. **404 Not Found** — Request data transaksi dengan ID yang tidak terdaftar.

---
