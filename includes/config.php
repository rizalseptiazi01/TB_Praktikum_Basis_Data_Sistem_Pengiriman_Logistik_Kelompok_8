<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistem_pengiriman_logistik');

// Koneksi PDO
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Untuk demo tampilan
    $pdo = null;
    $db_error = $e->getMessage();
}

// Helper: Format Rupiah
function rupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}

// Helper: Format Tanggal Indonesia
function tgl_indo($date) {
    if (!$date) return '-';
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $d = date('d', strtotime($date));
    $m = (int)date('m', strtotime($date));
    $y = date('Y', strtotime($date));
    return $d . ' ' . $bulan[$m] . ' ' . $y;
}

// Helper: Badge Status
function badge_status($status) {
    $map = [
        'pending'    => ['amber', 'Menunggu'],
        'pickup'     => ['blue',  'Pickup'],
        'in_transit' => ['cyan',  'Dalam Perjalanan'],
        'delivered'  => ['green', 'Terkirim'],
        'failed'     => ['red',   'Gagal'],
        'aktif'      => ['green', 'Aktif'],
        'nonaktif'   => ['red',   'Nonaktif'],
        'cuti'       => ['amber', 'Cuti'],
    ];
    $s = strtolower($status ?? '');
    [$cls, $label] = $map[$s] ?? ['gray', ucfirst($status)];
    return "<span class='badge badge-{$cls}'>{$label}</span>";
}

// Aktif halaman
$page = $_GET['page'] ?? 'dashboard';
?>
