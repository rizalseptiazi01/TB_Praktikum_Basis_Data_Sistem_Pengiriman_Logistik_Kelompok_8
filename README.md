# <img width="512" height="512" alt="Logo_Logistik" src="https://github.com/user-attachments/assets/95da0051-26de-42c9-9081-7ec8e8cb21aa" /> LogiTrackTG - Sistem Pengiriman Logistik (Kelompok 8)
Tugas Besar Praktikum Basis Data - Institut Teknologi Garut / Teknik Informatika A] 2026

Sistem informasi berbasis web dinamis untuk mempermudah manajemen manifest pengiriman barang, pendataan customer, pelacakan kurir, serta rekapitulasi pendapatan logistik secara real-time.

---

## 👥 Anggota Kelompok 8
* **Haikal Muhamad Sabqi**    (NIM: 2406020)
* **Rizal Septiazi**          (NIM: 2406034) 
* **Muhamad Rojaka**          (NIM: 2406027)
* **Muhammad Saepul Hidayat** (NIM: 2406025)

## 🚀 Fitur Utama Aplikasi
* **Dashboard Analytics:** Menampilkan total akumulasi data penting secara real-time (Total Customer, Total Pengiriman, Kurir Aktif, dan Total Pendapatan).
* Tujuan : Menjadi halaman utama (pusat kendali) bagi Administrator untuk melihat ringkasan performa operasional logistik secara instan.
<img width="1295" height="637" alt="Dashboard" src="https://github.com/user-attachments/assets/42e8d16a-afa5-4dfa-aee4-743932086120" />

* **Daftar Customer:** Menyimpan informasi identitas customer (seperti nama, nomor telepon, dan alamat asal) untuk mempermudah pencatatan saat pembuatan manifest pengiriman baru.
* Tujuan : Mengelola data seluruh pelanggan atau pihak yang menggunakan jasa pengiriman LogiTrack
<img width="1269" height="634" alt="Customer" src="https://github.com/user-attachments/assets/29212015-2e4b-484b-b058-c567a72ebf59" />

* **Daftar Barang:** Menyimpan detail spesifikasi barang (seperti nama barang, berat default, atau satuan ukuran) guna memastikan perhitungan tarif pengiriman dan kapasitas muat menjadi lebih akurat.
* Tujuan : Mengelola katalog jenis atau kategori barang yang sering dikirimkan melalui sistem.
<img width="1284" height="626" alt="Barang" src="https://github.com/user-attachments/assets/0d2faaf5-79ef-4c74-a231-02824105178c" />

* **Daftar Kurir:** Mencatat data pribadi kurir, nomor kendaraan, serta status operasional mereka (apakah sedang 'aktif' bekerja, libur, atau cuti) sebelum diberikan tugas pengiriman.
* Tujuan : Mengelola informasi lengkap mengenai para petugas pengantar (kurir) yang terdaftar di dalam sistem.
<img width="1279" height="632" alt="Kurir" src="https://github.com/user-attachments/assets/2a0b62af-0621-4bcb-a874-5cc1e6355117" />

## 🛠️ Teknologi yang Digunakan
* **Backend:** PHP 8.x (PDO MySQL Object Configuration)
* **Frontend:** HTML5, CSS3, JavaScript
* **Database:** MySQL

## 🗃️ Cara Instalasi di Lokal/Laptop
1. Download atau Clone repositori ini ke folder `htdocs` XAMPP Anda.
2. Buat database baru bernama `db_logistik` (atau sesuaikan) di phpMyAdmin, lalu import file `.sql`.
3. Sesuaikan konfigurasi database pada file di folder `includes/`.
4. Jalankan Apache dan MySQL di XAMPP, lalu akses melalui browser di `http://localhost/logistik`.
