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
<header class="topbar" id="topbar">
  <button class="topbar-btn" id="menuToggle" style="margin-right:4px; cursor: pointer;">☰</button>

  <div style="flex:1">
    <div class="topbar-title"><?= $title ?></div>
    <div class="topbar-breadcrumb">LogiTrackITG › <?= $title ?></div>
  </div>

  <div class="topbar-actions">
    <div class="topbar-profile">
      <div class="topbar-avatar">A</div>
      <span class="topbar-username">Admin</span>
    </div>
  </div>
</header>

<style>
.topbar {
  transition: all 0.3s ease-in-out !important;
}

body.sidebar-hidden .topbar {
  left: 0 !important;
  width: 100% !important;
  max-width: 100% !important;
  padding-left: 20px !important;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const menuToggleTopbar = document.getElementById("menuToggle");
  const sidebar = document.getElementById("sidebar");
  const body = document.body;

  if (menuToggleTopbar) {
    menuToggleTopbar.addEventListener("click", function(e) {
      e.preventDefault();
      
      if (sidebar) {
        sidebar.classList.toggle("minimized");
        body.classList.toggle("sidebar-hidden");
      }
    });
  }
});
</script>
