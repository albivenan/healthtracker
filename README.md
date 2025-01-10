# HealthTracker

Aplikasi pengelolaan kesehatan pribadi berbasis web yang memungkinkan pengguna mencatat, memonitor, dan menganalisis data kesehatan harian.

## Fitur

- Pencatatan data kesehatan (berat badan, tekanan darah)
- Rekomendasi pola makan
- Pelacakan aktivitas fisik
- Visualisasi data kesehatan dalam bentuk grafik
- Autentikasi pengguna

## Teknologi yang Digunakan

- PHP 7.4+
- MongoDB
- Tailwind CSS
- Chart.js

## Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MongoDB Server
- Composer
- Web Server (Apache/Nginx)

## Instalasi

1. Clone repositori ini:
```bash
git clone https://github.com/username/healthtracker.git
cd healthtracker
```

2. Install dependensi menggunakan Composer:
```bash
composer install
```

3. Salin file konfigurasi:
```bash
cp config/database.example.php config/database.php
```

4. Sesuaikan konfigurasi database di `config/database.php`

5. Pastikan direktori `storage` dapat ditulis:
```bash
chmod -R 777 storage
```

6. Konfigurasi web server Anda untuk mengarahkan ke direktori `public`

## Penggunaan

1. Buat akun baru melalui halaman registrasi
2. Login menggunakan email dan password
3. Mulai mencatat data kesehatan harian Anda
4. Pantau perkembangan kesehatan melalui dashboard

## Struktur Direktori

```
healthtracker/
├── config/             # File konfigurasi
├── public/             # Dokumen publik
├── src/                # Source code
│   ├── Controllers/    # Controller
│   ├── Models/         # Model
│   └── Database/       # Koneksi database
├── views/              # Template view
├── storage/            # File yang dapat ditulis
├── vendor/             # Dependensi
└── composer.json       # Konfigurasi Composer
```

## Kontribusi

1. Fork repositori
2. Buat branch fitur (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -am 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE). 