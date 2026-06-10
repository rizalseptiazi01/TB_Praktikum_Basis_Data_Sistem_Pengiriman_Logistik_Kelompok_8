<?php
$page_titles = [
    'dashboard'      => ['Dashboard',        'Selamat datang kembali, Admin!'],
    'customer'       => ['Data Customer',     'Kelola data pelanggan'],
    'barang'         => ['Data Barang',       'Manajemen katalog barang'],
    'kurir'          => ['Data Kurir',        'Manajemen tenaga pengiriman'],
    'pengiriman'     => ['Data Pengiriman',   'Manajemen transaksi pengiriman'],
    'penugasan'      => ['Penugasan Kurir',   'Atur penugasan kurir per rute'],
    'riwayat_status' => ['Riwayat Status',    'Lacak perubahan status pengiriman'],
    'laporan'        => ['Laporan',           'Analitik dan laporan performa'],
];
[$title, $subtitle] = $page_titles[$page] ?? ['Halaman', ''];
?>
<header class="topbar">
  <button class="topbar-btn" id="menuToggle" style="margin-right:4px">☰</button>

  <div style="flex:1">
    <div class="topbar-title"><?= $title ?></div>
    <div class="topbar-breadcrumb">LogiTrackITG › <?= $title ?></div>
  </div>

  <div class="topbar-search">
    <span class="search-icon">🔍</span>
    <input type="text" placeholder="Cari pengiriman, customer...">
  </div>

  <div class="topbar-actions">
    <div class="topbar-btn" title="Pengaturan">⚙️</div>
    <div class="topbar-profile">
      <div class="topbar-avatar">A</div>
      <span class="topbar-username">Admin</span>
      <span style="color:var(--gray-400);font-size:11px">▼</span>
    </div>
  </div>
</header>