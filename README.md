# POS & Inventory Management System

**POS Inventory** adalah aplikasi Point of Sale dan Manajemen Inventory berbasis web yang dirancang untuk bisnis retail skala kecil hingga menengah. Aplikasi ini mendukung multi-cabang, role-based access, dan dilengkapi laporan lengkap untuk analisis bisnis.

> **Tech Stack:** Laravel 13, Vue 3 + Inertia.js, Pinia, Tailwind CSS 4, MySQL, Vite 8
> **Role:** Admin, Kasir, Owner
> **Fitur Unggulan:** Barcode scanner, WhatsApp notifikasi, PWA, Dark Mode, Multi-cabang

## Latar Belakang

Proyek ini dibangun untuk menggantikan sistem POS konvensional yang:
- ❌ Tidak memiliki integrasi stok real-time antar cabang
- ❌ Tidak mendukung peran pengguna yang berbeda (kasir, owner, admin)
- ❌ Laporan bisnis masih manual dan tidak akurat
- ❌ Tidak ada notifikasi stok minimum otomatis

**POS Inventory** menjawab permasalahan tersebut dengan arsitektur multi-cabang, role-based access, dan otomatisasi laporan serta notifikasi.

## Fitur Lengkap

### POS Terminal
- Pencarian produk real-time (nama, SKU, barcode)
- Barcode scanner via kamera (`html5-qrcode`)
- Diskon per-item (persentase) dan diskon global transaksi
- Hold cart (simpan sementara + resume)
- Void transaksi (khusus admin)
- Cetak struk: browser print & thermal ESC/POS (`mike42/escpos-php`)
- Cart persist ke localStorage

### Manajemen Produk
- CRUD produk dengan upload gambar
- Auto-generate SKU: `{3 huruf kategori}{YYYYMMDD}{6 random digit}`
- Auto-generate barcode: `{YYYYMMDD}{6 random digit}` (format CODE128)
- Stok terdistribusi per cabang (`branch_product` pivot)
- Cetak label barcode (A4 2 kolom)

### Multi Cabang
- CRUD cabang
- Stok terpisah per cabang (tidak ada kolom `products.stock`)
- Transfer stok antar cabang
- Laporan perbandingan cabang

### Role-Based Access
| Fitur | Admin | Kasir | Owner |
|-------|-------|-------|-------|
| POS Transaksi | ✅ | ✅ | ❌ |
| Kelola Produk | ✅ | ❌ | ✅ |
| Barang Masuk/Keluar | ✅ | ❌ | ❌ |
| Transfer Stok | ✅ | ❌ | ❌ |
| Laporan Penjualan | ✅ | ✅ (terbatas) | ✅ |
| Laporan Keuangan | ✅ | ❌ | ✅ |
| Laporan Inventory | ✅ | ❌ | ✅ |
| Dashboard | ✅ | ✅ (terbatas) | ✅ |
| Kelola User | ✅ | ❌ | ❌ |
| Purchase Order | ✅ | ❌ | ✅ |
| Pengaturan | ✅ | ❌ | ❌ |

### Purchase Order
- CRUD purchase order
- Workflow: Create → Approve → Receive/Cancel
- Otomatis tambah stok saat receive
- Dikirim ke cabang tertentu

### Laporan & Ekspor
- **Laporan Penjualan** — Filter harian/mingguan/bulanan, top produk
- **Laporan Inventory** — Filter stok minimum/habis
- **Laporan Keuangan** — Omzet, HPP, laba kotor/bersih
- **Perbandingan Cabang** — Perbandingan performa antar cabang
- **Ekspor:** Excel (`maatwebsite/excel`) & PDF (`barryvdh/laravel-dompdf`)

### Notifikasi WhatsApp
- Gateway: **Fonnte**
- Low stock alert (otomatis tiap jam via scheduler)
- Daily report (otomatis tiap tengah malam)
- Struk penjualan ("Kirim ke WhatsApp" button di detail sale)
- Nomor tujuan diambil dari field `whatsapp_phone` user

### PWA (Progressive Web App)
- Install sebagai aplikasi di HP/desktop
- Service worker auto-generated (`vite-plugin-pwa`)
- Manifest icon SVG
- Theme-color dinamis (light/dark)

### Dark Mode
- Toggle di topbar (icon ☀️/🌙)
- Persist ke localStorage
- Fallback ke preferensi sistem
- Semua komponen support dark variants

### Dashboard
- Stat cards: Penjualan Hari Ini, Omzet Bulan Ini, Laba Kotor, Stok Minimum
- Grafik penjualan 7 hari (Chart.js via Componable)
- Top 5 produk terlaris
- Alert stok minimum
- Quick actions (shortcut ke semua menu)
- Data discope per kasir (hanya lihat transaksi sendiri)

## User Seed

Setelah migrasi dan seed, akun berikut tersedia untuk login:

| Email | Password | Role | Akses |
|-------|----------|------|-------|
| `admin@pos.test` | `password` | **Admin** | Full akses — produk, stok, user, laporan, pengaturan |
| `kasir@pos.test` | `password` | **Kasir** | POS + Penjualan + Laporan Penjualan (terbatas) |
| `owner@pos.test` | `password` | **Owner** | Produk, laporan, dashboard (tanpa POS/transaksi) |

## Instalasi

```bash
# Clone & masuk direktori
git clone <repo-url>
cd pos-system

# Install dependencies
composer install
npm install --ignore-scripts

# Environment
cp .env.example .env
php artisan key:generate

# Database (MySQL)
php artisan migrate --seed

# Build frontend
npm run build

# Jalankan dev server
php artisan serve
```

Atau gunakan perintah satu baris:

```bash
composer setup
```

## Dev Server

```bash
composer dev
```

Menjalankan secara concurrent: `php artisan serve`, `queue:listen`, `pail` (logs), `npm run dev` (Vite HMR).

## Testing

```bash
composer test
```

34 test, 78 assertions (SQLite in-memory).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
