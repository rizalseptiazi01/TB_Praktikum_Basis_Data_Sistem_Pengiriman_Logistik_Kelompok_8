# 📦 LogiTrackTG - Sistem Pengiriman Logistik (Kelompok 8)
Tugas Besar Praktikum Basis Data - Institut Teknologi Garut / Teknik Informatika A] 2026

Sistem informasi berbasis web dinamis untuk mempermudah manajemen manifest pengiriman barang, pendataan customer, pelacakan kurir, serta rekapitulasi pendapatan logistik secara real-time.

---

## 👥 Anggota Kelompok 8
* **Haikal Muhamad Sabqi**    (NIM: 2406020)
* **Rizal Septiazi**          (NIM: 2406034) 
* **Muhamad Rojaka**          (NIM: 2406027)
* **Muhammad Saepul Hidayat** (NIM: 2406025)

## 🚀 Fitur Utama Aplikasi
* **Dashboard Analytics:** Ringkasan total customer, total omset, serta grafik tren pengiriman bulanan.
* **Aktivitas Terbaru (Timeline):** Log otomatis transaksi manifest dan pendaftaran customer baru secara live.
* **Tabel Pengiriman Terbaru:** Menampilkan 5 manifest pengiriman terakhir secara bersih dan terurut dari database.
* **Status Penugasan Kurir:** Grafik pembagian tugas kurir (*Delivered* dan *In Transit*).

## 🛠️ Teknologi yang Digunakan
* **Backend:** PHP 8.x (PDO MySQL Object Configuration)
* **Frontend:** HTML5, CSS3, JavaScript
* **Database:** MySQL

## 🗃️ Cara Instalasi di Lokal/Laptop
1. Download atau Clone repositori ini ke folder `htdocs` XAMPP Anda.
2. Buat database baru bernama `db_logistik` (atau sesuaikan) di phpMyAdmin, lalu import file `.sql`.
3. Sesuaikan konfigurasi database pada file di folder `includes/`.
4. Jalankan Apache dan MySQL di XAMPP, lalu akses melalui browser di `http://localhost/logistik`.
