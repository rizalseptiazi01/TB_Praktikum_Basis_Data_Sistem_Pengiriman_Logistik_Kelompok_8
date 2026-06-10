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
  <div class="sidebar-brand">
    <a href="?page=dashboard" class="brand-logo">
      <div class="brand-icon">🚛</div>
      <div class="brand-text">
        <div class="brand-name">LogiTrackITG</div>
        <div class="brand-sub">Sistem Pengiriman</div>
      </div>
    </a>
  </div>

  <nav class="sidebar-nav">
    <?php foreach ($nav_items as $item): ?>
      <?php if (isset($item['section'])): ?>
        <div class="nav-section-label"><?= $item['section'] ?></div>
      <?php else: ?>
        <a href="?page=<?= $item['page'] ?>"
           class="nav-item <?= $page === $item['page'] ? 'active' : '' ?>">
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
