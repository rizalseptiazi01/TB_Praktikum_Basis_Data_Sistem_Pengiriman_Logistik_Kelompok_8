<?php
// Ambil data dari database jika tersedia
$total_customer    = $pdo ? $pdo->query("SELECT COUNT(*) FROM customer")->fetchColumn() : 248;
$total_pengiriman  = $pdo ? $pdo->query("SELECT COUNT(*) FROM pengiriman")->fetchColumn() : 1532;
$total_kurir       = $pdo ? $pdo->query("SELECT COUNT(*) FROM kurir WHERE status_kurir='aktif'")->fetchColumn() : 47;
$total_pendapatan  = $pdo ? $pdo->query("SELECT SUM(total_biaya) FROM pengiriman")->fetchColumn() : 384750000;
$pengiriman_selesai = $pdo ? $pdo->query("SELECT COUNT(*) FROM penugasan_kurir WHERE status_tugas='delivered'")->fetchColumn() : 1280;
$pengiriman_proses  = $pdo ? $pdo->query("SELECT COUNT(*) FROM penugasan_kurir WHERE status_tugas='in_transit'")->fetchColumn() : 152;

// Data dummy untuk chart
$chart_data = [
    ['bulan'=>'Jan','val'=>85],['bulan'=>'Feb','val'=>72],['bulan'=>'Mar','val'=>95],
    ['bulan'=>'Apr','val'=>110],['bulan'=>'Mei','val'=>98],['bulan'=>'Jun','val'=>125],
    ['bulan'=>'Jul','val'=>140],
];
$max_val = max(array_column($chart_data, 'val'));

// Pengiriman terbaru (Dipastikan mengambil yang ID-nya paling besar/baru)
$pengiriman_terbaru = $pdo ? $pdo->query(
    "SELECT p.id_pengiriman, p.id_customer, p.tgl_pengiriman, p.total_biaya, p.kota_tujuan, c.nama_customer FROM pengiriman p 
     LEFT JOIN customer c ON p.id_customer=c.id_customer 
     ORDER BY p.id_pengiriman DESC LIMIT 5"
)->fetchAll() : [
    ['id_pengiriman'=>'PGM-0001','nama_customer'=>'Budi Santoso','kota_tujuan'=>'Surabaya','total_biaya'=>125000,'tgl_pengiriman'=>date('Y-m-d')],
    ['id_pengiriman'=>'PGM-0002','nama_customer'=>'Siti Rahayu','kota_tujuan'=>'Bandung','total_biaya'=>85000,'tgl_pengiriman'=>date('Y-m-d')],
    ['id_pengiriman'=>'PGM-0003','nama_customer'=>'Ahmad Fauzi','kota_tujuan'=>'Medan','total_biaya'=>320000,'tgl_pengiriman'=>date('Y-m-d')],
    ['id_pengiriman'=>'PGM-0004','nama_customer'=>'Dewi Lestari','kota_tujuan'=>'Yogyakarta','total_biaya'=>95000,'tgl_pengiriman'=>date('Y-m-d')],
    ['id_pengiriman'=>'PGM-0005','nama_customer'=>'Hendra Wijaya','kota_tujuan'=>'Makassar','total_biaya'=>450000,'tgl_pengiriman'=>date('Y-m-d')],
];

// =========================================================================
// PERBAIKAN LOGIKA URUTAN: Menggunakan ID agar data yang baru disimpan langsung naik ke atas
// =========================================================================
$aktivitas_list = [];
if ($pdo) {
    try {
        // Kita gunakan ID masing-masing sebagai pengurut utama (di-CAST/CONVERT agar setara dalam UNION)
        $queryAkt = "
            (SELECT 
                id_pengiriman AS id_sort,
                tgl_pengiriman AS waktu_log,
                'pengiriman' AS jenis,
                CONCAT('Pengiriman baru dibuat: <strong>', id_pengiriman, '</strong>') AS judul,
                CONCAT(kota_tujuan, ' • Baru saja') AS sub_desc
             FROM pengiriman)
            UNION
            (SELECT 
                CONCAT('CUST-', id_customer) AS id_sort, 
                NULL AS waktu_log,
                'customer' AS jenis,
                'Customer baru terdaftar' AS judul,
                CONCAT(nama_customer, ' • Baru saja') AS sub_desc
             FROM customer)
            ORDER BY id_sort DESC 
            LIMIT 5";
            
        $aktivitas_list = $pdo->query($queryAkt)->fetchAll();
    } catch (PDOException $e) {
        // Jika ada error query, tangkap di sini agar halaman tidak blank
        echo "<script>console.log('Error Aktivitas: " . addslashes($e->getMessage()) . "');</script>";
        $aktivitas_list = [];
    }
}
?>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon-wrap blue">👥</div>
    <div class="stat-info">
      <div class="stat-value"><?= number_format($total_customer) ?></div>
      <div class="stat-label">Total Customer</div>
      <div class="stat-change up">▲ 12% bulan ini</div>
    </div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon-wrap green">📦</div>
    <div class="stat-info">
      <div class="stat-value"><?= number_format($total_pengiriman) ?></div>
      <div class="stat-label">Total Pengiriman</div>
      <div class="stat-change up">▲ 8% bulan ini</div>
    </div>
  </div>
  <div class="stat-card amber">
    <div class="stat-icon-wrap amber">🚚</div>
    <div class="stat-info">
      <div class="stat-value"><?= number_format($total_kurir) ?></div>
      <div class="stat-label">Kurir Aktif</div>
      <div class="stat-change down">▼ 2 kurir cuti</div>
    </div>
  </div>
  <div class="stat-card cyan">
    <div class="stat-icon-wrap cyan">💰</div>
    <div class="stat-info">
      <div class="stat-value"><?= 'Rp ' . number_format($total_pendapatan/1000000, 1) . 'Jt' ?></div>
      <div class="stat-label">Total Pendapatan</div>
      <div class="stat-change up">▲ 15% bulan ini</div>
    </div>
  </div>
</div>

<div class="dashboard-grid">
  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">📈 Tren Pengiriman 2025</div>
        <div class="card-subtitle">Jumlah pengiriman per bulan</div>
      </div>
    </div>
    <div class="card-body">
      <div class="bar-chart">
        <?php foreach ($chart_data as $d): ?>
          <?php $h = round(($d['val'] / $max_val) * 100); ?>
          <div class="bar-item">
            <span style="font-size:10px;color:var(--blue-700);font-weight:600"><?= $d['val'] ?></span>
            <div class="bar" style="height:<?= $h ?>%;background:<?= $h==100 ? 'var(--blue-500)' : 'var(--blue-200)' ?>"></div>
            <span class="bar-label"><?= $d['bulan'] ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">🟢 Status Penugasan Kurir</div>
        <div class="card-subtitle">Distribusi status dari modul kurir</div>
      </div>
    </div>
    <div class="card-body" style="text-align:center">
      <div class="donut-wrap">
        <svg class="donut-svg" viewBox="0 0 42 42">
          <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#e8f4fd" stroke-width="5"/>
          <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="var(--success)" stroke-width="5"
                  stroke-dasharray="83 17" stroke-dashoffset="25" style="transition:stroke-dasharray 0.6s"/>
          <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="var(--info)" stroke-width="5"
                  stroke-dasharray="10 90" stroke-dashoffset="-58"/>
          <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="var(--warning)" stroke-width="5"
                  stroke-dasharray="7 93" stroke-dashoffset="-68"/>
        </svg>
        <div class="donut-center">
          <div class="donut-value"><?= $pengiriman_selesai ?></div>
          <div class="donut-desc">Terkirim</div>
        </div>
      </div>
      <div style="text-align:left">
        <div class="legend-item">
          <div class="legend-dot" style="background:var(--success)"></div>
          <span style="flex:1;font-size:13px">Terkirim</span>
          <span style="font-weight:600;color:var(--blue-900)"><?= $pengiriman_selesai ?></span>
        </div>
        <div class="legend-item">
          <div class="legend-dot" style="background:var(--info)"></div>
          <span style="flex:1;font-size:13px">Dalam Perjalanan</span>
          <span style="font-weight:600;color:var(--blue-900)"><?= $pengiriman_proses ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="dashboard-grid">
  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">📋 Pengiriman Terbaru</div>
        <div class="card-subtitle">5 pengiriman terakhir</div>
      </div>
      <a href="?page=pengiriman" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>ID Pengiriman</th>
            <th>Customer</th>
            <th>Tujuan</th>
            <th>Biaya</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pengiriman_terbaru as $row): ?>
          <tr>
            <td><strong><?= htmlspecialchars($row['id_pengiriman']) ?></strong></td>
            <td><?= htmlspecialchars($row['nama_customer'] ?? '-') ?></td>
            <td>📍 <?= htmlspecialchars($row['kota_tujuan']) ?></td>
            <td style="font-weight:700; color:var(--blue-700);"><?= rupiah($row['total_biaya']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title">🕐 Aktivitas Terbaru</div>
    </div>
    <div class="card-body">
      <div class="timeline">
        <?php if (empty($aktivitas_list)): ?>
          <div style="text-align:center; padding: 40px 10px; color: var(--gray-400); font-size: 13px;">
            <div style="font-size: 28px; margin-bottom: 8px;">📭</div>
            Belum ada aktivitas transaksi terbaru.
          </div>
        <?php else: ?>
          <?php foreach ($aktivitas_list as $akt): ?>
            <div class="timeline-item">
              <?php 
                $dot_class = 'blue';
                $dot_icon = '📦';
                if ($akt['jenis'] === 'customer') {
                    $dot_class = 'amber';
                    $dot_icon = '+';
                }
              ?>
              <div class="timeline-dot <?= $dot_class ?>"><?= $dot_icon ?></div>
              <div class="timeline-content">
                <div class="timeline-title"><?= $akt['judul'] ?></div>
                <div class="timeline-desc"><?= htmlspecialchars($akt['sub_desc']) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>