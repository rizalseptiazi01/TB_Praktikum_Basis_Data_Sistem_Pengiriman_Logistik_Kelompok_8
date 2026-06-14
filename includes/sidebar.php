<?php
$nav_items = [
    ['page' => 'dashboard',         'icon' => '📊', 'label' => 'Dashboard'],
    ['section' => 'Manajemen Data'],
    ['page' => 'customer',          'icon' => '👥', 'label' => 'Data Customer'],
    ['page' => 'barang',            'icon' => '📦', 'label' => 'Data Barang'],
    ['page' => 'kurir',             'icon' => '🚚', 'label' => 'Data Kurir'],
    ['section' => 'Pengiriman'],
    ['page' => 'pengiriman',        'icon' => '🗺️', 'label' => 'Data Pengiriman'],
    ['page' => 'penugasan',         'icon' => '📋', 'label' => 'Penugasan Kurir'],
    ['page' => 'riwayat_status',    'icon' => '🕐', 'label' => 'Riwayat Status'],
    ['section' => 'Analitik'],
    ['page' => 'laporan',           'icon' => '📈', 'label' => 'Laporan'],
];
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand" style="display: flex; align-items: center; padding: 20px 15px; gap: 10px;">
    <a href="?page=dashboard" class="brand-logo" style="display: flex; align-items: center; gap: 10px; text-decoration: none; flex: 1;">
      <img src="assets/img/Logo_Logistik.png" alt="Logo" style="width: 90px; height: 90px; object-fit: contain; flex-shrink: 0;">
      <div class="brand-text" style="display: flex; flex-direction: column; justify-content: center;">
        <div class="brand-name" style="font-weight: 700; color: #ffffff; font-size: 18px; letter-spacing: 0.5px; line-height: 1.2;">LogiTrackITG</div>
        <div class="brand-sub" style="font-size: 9px; color: #94a3b8; font-weight: 600; letter-spacing: 1px; margin-top: 2px;">Sistem Pengiriman</div>
      </div>
    </a>
  </div>

  <nav class="sidebar-nav">
    <?php foreach ($nav_items as $item): ?>
      <?php if (isset($item['section'])): ?>
        <div class="nav-section-label"><?= $item['section'] ?></div>
      <?php else: ?>
        <a href="?page=<?= $item['page'] ?>"
           class="nav-item <?= (isset($page) && $page === $item['page']) ? 'active' : '' ?>">
          <span class="nav-icon"><?= $item['icon'] ?></span>
          <?= $item['label'] ?>
          <?php if ($item['page'] === 'pengiriman'): ?>
            <span class="nav-badge">3</span>
          <?php endif; ?>
        </a>
      <?php endif; ?>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar-footer">
    <div class="user-card">
      <div class="user-avatar">A</div>
      <div>
        <div class="user-name">Admin</div>
        <div class="user-role">Super Administrator</div>
      </div>
    </div>
  </div>
</aside>

<style>
.sidebar {
  transition: all 0.3s ease-in-out !important;
}
.sidebar.minimized {
  margin-left: -260px !important; 
}
.main-content, .main, #main-wrapper {
  transition: all 0.3s ease-in-out !important;
}
body.sidebar-hidden .main-content,
body.sidebar-hidden .main,
body.sidebar-hidden #main-wrapper {
  margin-left: 0 !important;
  padding-left: 20px !important;
  width: 100% !important;
  max-width: 100% !important;
}
</style>
