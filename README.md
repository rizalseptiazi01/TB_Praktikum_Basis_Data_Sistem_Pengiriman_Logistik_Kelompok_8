# <img width="1024" height="1024" alt="Logo_Logistik" src="https://github.com/user-attachments/assets/c38c87fb-b803-4c6e-ab28-2aea353ccbd2" /> LogiTrackTG - Sistem Pengiriman Logistik (Kelompok 8)
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

* **Data Customer:** Menyimpan informasi identitas customer (seperti nama, nomor telepon, dan alamat asal) untuk mempermudah pencatatan saat pembuatan manifest pengiriman baru.
* Tujuan : Mengelola data seluruh pelanggan atau pihak yang menggunakan jasa pengiriman LogiTrack
<img width="1269" height="634" alt="Customer" src="https://github.com/user-attachments/assets/29212015-2e4b-484b-b058-c567a72ebf59" />

* **Data Barang:** Menyimpan detail spesifikasi barang (seperti nama barang, berat default, atau satuan ukuran) guna memastikan perhitungan tarif pengiriman dan kapasitas muat menjadi lebih akurat.
* Tujuan : Mengelola katalog jenis atau kategori barang yang sering dikirimkan melalui sistem.
<img width="1284" height="626" alt="Barang" src="https://github.com/user-attachments/assets/0d2faaf5-79ef-4c74-a231-02824105178c" />

* **Data Kurir:** Mencatat data pribadi kurir, nomor kendaraan, serta status operasional mereka (apakah sedang 'aktif' bekerja, libur, atau cuti) sebelum diberikan tugas pengiriman.
* Tujuan : Mengelola informasi lengkap mengenai para petugas pengantar (kurir) yang terdaftar di dalam sistem.
<img width="1279" height="632" alt="Kurir" src="https://github.com/user-attachments/assets/2a0b62af-0621-4bcb-a874-5cc1e6355117" />

* **Data Pengiriman:** Tempat admin membuat baris pengiriman baru, menginput nama penerima, kota tujuan, menghitung total biaya kirim, serta mengelola rincian item barang yang dimasukkan ke dalam paket melalui jendela popup (modal).
* Tujuan : Mencatat dan mengelola seluruh berkas manifest atau resi transaksi pengiriman barang.
<img width="1288" height="629" alt="Pengiriman" src="https://github.com/user-attachments/assets/569c5e54-2846-4ed9-baf6-004c5b94e6f4" />
<img width="1280" height="615" alt="Screenshot (383)" src="https://github.com/user-attachments/assets/230676fa-a16a-4141-955d-cdfff23f6ad6" />


* **Penugasan Kurir:** Menetapkan kurir spesifik untuk bertanggung jawab membawa paket tertentu, serta menjadi tempat memperbarui status kerja kurir tersebut. Status tugas ini akan terbagi secara dinamis ke dalam status Pickup, Menunggu, Dalam Perjalanan, Terkirim, hingga Gagal.
* Tujuan : Menghubungkan (plotting) antara berkas paket pengiriman yang ada dengan kurir yang akan mengeksekusinya di lapangan.
<img width="1273" height="633" alt="Penugasan_Kurir" src="https://github.com/user-attachments/assets/8da9b2f0-3567-4317-9f4c-3dabe72cfb23" />

* **Riwayat_Status:** Menyimpan catatan setiap terjadi perubahan status logistik lengkap dengan informasi lokasi check-point (misal: "Gudang Bandung") dan catatan internal dari kurir.
* Tujuan : Menyediakan pencatatan jejak audit (audit trail) atau log perjalanan dari setiap paket secara kronologis.
<img width="1280" height="640" alt="Screenshot (384)" src="https://github.com/user-attachments/assets/fe6089cc-293f-43b7-ae0d-3aeff5748efe" />

* **Laporan_Analitik:** Menyajikan ringkasan indikator kinerja utama seperti total transaksi bulanan, rata-rata biaya kirim, tingkat keberhasilan pengiriman, serta tabel rekap pendapatan bersih. Menu ini juga sudah dilengkapi dengan fitur Cetak Halaman yang terintegrasi langsung dengan fungsionalitas print browser.
* Tujuan : Menyusun rangkuman data performa bisnis pengiriman dalam periode tertentu (bulanan/tahunan) untuk kebutuhan cetak dokumen fisik.
<img width="1288" height="639" alt="Laporan" src="https://github.com/user-attachments/assets/a2bb2437-6f59-4a2f-bab4-3fe6a7ea4dc2" />




## 🛠️ Teknologi yang Digunakan
* **Backend:** PHP 8.x (PDO MySQL Object Configuration)
* **Frontend:** HTML5, CSS3, JavaScript
* **Database:** MySQL

## 🗃️ Cara Instalasi di Lokal/Laptop
1. Download atau Clone repositori ini ke folder `htdocs` XAMPP .
2. Buat database baru bernama `sistem_pengiriman_logistik` (atau sesuaikan) di phpMyAdmin, lalu import file `.sql`.
3. Sesuaikan konfigurasi database pada file di folder `includes/`.
4. Jalankan Apache dan MySQL di XAMPP, lalu akses melalui browser di `http://localhost/logistik`.
