<?php
// Ambil data dari database jika tersedia
$total_customer    = $pdo ? $pdo->query("SELECT COUNT(*) FROM customer")->fetchColumn() : 248;
$total_pengiriman  = $pdo ? $pdo->query("SELECT COUNT(*) FROM pengiriman")->fetchColumn() : 1532;
$total_kurir       = $pdo ? $pdo->query("SELECT COUNT(*) FROM kurir WHERE status_kurir='aktif'")->fetchColumn() : 47;
// Sudah dinamis: Mengambil jumlah kurir yang berstatus 'cuti' dari database
$total_kurir_cuti  = $pdo ? $pdo->query("SELECT COUNT(*) FROM kurir WHERE status_kurir='cuti'")->fetchColumn() : 2; 
$total_pendapatan  = $pdo ? $pdo->query("SELECT SUM(total_biaya) FROM pengiriman")->fetchColumn() : 384750000;

// Ambil Semua Status Penugasan 
$status_counts = [
    'Terkirim'         => 0,
    'Dalam Perjalanan' => 0,
    'Pickup'           => 0,
    'Menunggu'         => 0,
    'Gagal'            => 0
];

if ($pdo) {
    try {
        // Mengambil jumlah data berdasarkan status_tugas di database
        $stmtStatus = $pdo->query("SELECT status_tugas, COUNT(*) as jumlah FROM penugasan_kurir GROUP BY status_tugas");
        while ($rowStatus = $stmtStatus->fetch(PDO::FETCH_ASSOC)) {
            $st = $rowStatus['status_tugas'];
            
            // Mapping nama status dari database ke label tampilan dashboard
            if ($st == 'delivered') $st = 'Terkirim';
            if ($st == 'in_transit') $st = 'Dalam Perjalanan';
            if ($st == 'pickup') $st = 'Pickup';
            if ($st == 'pending' || $st == 'menunggu') $st = 'Menunggu';
            if ($st == 'failed' || $st == 'gagal') $st = 'Gagal';

            if (array_key_exists($st, $status_counts)) {
                $status_counts[$st] = intval($rowStatus['jumlah']);
            }
        }
    } catch (PDOException $e) {
        $status_counts = ['Terkirim' => 13, 'Dalam Perjalanan' => 0, 'Pickup' => 0, 'Menunggu' => 0, 'Gagal' => 0];
    }
} else {
    $status_counts = ['Terkirim' => 13, 'Dalam Perjalanan' => 4, 'Pickup' => 2, 'Menunggu' => 3, 'Gagal' => 1];
}

// Menghitung total seluruh tugas kurir
$total_tugas_kurir = array_sum($status_counts);
$total_tugas_untuk_pembagian = $total_tugas_kurir == 0 ? 1 : $total_tugas_kurir; // Mencegah error division by zero

// Skema warna hex untuk masing-masing status penugasan
$status_colors = [
    'Terkirim'         => 'var(--success, #28a745)', 
    'Dalam Perjalanan' => 'var(--info, #17a2b8)',    
    'Pickup'           => '#ff9f1c',                 
    'Menunggu'         => '#cc66ff',                 
    'Gagal'            => '#dc3545'                  
];

$chart_data = [];
if ($pdo) {
    try {
        // Query mengambil singkatan nama bulan (Jan, Feb, Mar) dan total pengiriman tahun ini
        $queryChart = "
            SELECT 
                DATE_FORMAT(tgl_pengiriman, '%b') AS bulan, 
                COUNT(*) AS val 
            FROM pengiriman 
            WHERE YEAR(tgl_pengiriman) = YEAR(CURDATE())
            GROUP BY MONTH(tgl_pengiriman), DATE_FORMAT(tgl_pengiriman, '%b')
            ORDER BY MONTH(tgl_pengiriman)
        ";
        $chart_data = $pdo->query($queryChart)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $chart_data = [];
    }
}
// Mengambil nilai tertinggi untuk kalkulasi tinggi grafik batang
$max_val = !empty($chart_data) ? max(array_column($chart_data, 'val')) : 1;
if ($max_val == 0) $max_val = 1; 
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

// LOGIKA URUTAN TIMELINE AKTIVITAS
$aktivitas_list = [];
if ($pdo) {
    try {
        $queryAkt = "
            (SELECT 
                id_pengiriman AS id_sort,
                'pengiriman' AS jenis,
                CONCAT('Pengiriman baru dibuat: <strong>', id_pengiriman, '</strong>') AS judul,
                CONCAT(kota_tujuan, ' • Baru saja') AS sub_desc
             FROM pengiriman)
            UNION
            (SELECT 
                CONCAT('CUST-', LPAD(id_customer, 4, '0')) AS id_sort, 
                'customer' AS jenis,
                'Customer baru terdaftar' AS judul,
                CONCAT(nama_customer, ' • Baru saja') AS sub_desc
             FROM customer)
            ORDER BY id_sort DESC 
            LIMIT 5";
            
        $aktivitas_list = $pdo->query($queryAkt)->fetchAll();
    } catch (PDOException $e) {
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
      <div class="stat-change down">▼ <?= number_format($total_kurir_cuti) ?> kurir cuti</div>
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

<div class="card">
  <div class="card-header">
    <div>
      <div class="card-title">📈 Tren Pengiriman <?= date('Y') ?></div>
      <div class="card-subtitle">Jumlah transaksi manifest per bulan</div>
    </div>
  </div>
  <div class="card-body">
    <?php if (empty($chart_data)): ?>
      <div style="text-align:center; padding: 40px 10px; color: var(--gray-400, #9ca3af); font-size: 13px;">
        <div style="font-size: 32px; margin-bottom: 8px;">📊</div>
        Belum ada data pengiriman pada tahun ini.
      </div>
    <?php else: ?>
      <div class="bar-chart" style="display: flex; align-items: flex-end; justify-content: space-around; height: 180px; padding-top: 25px; gap: 10px;">
        <?php foreach ($chart_data as $d): ?>
          <?php 
            // Menghitung tinggi persentase secara presisi murni dari perbandingan data
            $h = round(($d['val'] / $max_val) * 100); 
          ?>
          <div class="bar-item" style="display: flex; flex-direction: column; align-items: center; flex: 1; height: 100%; justify-content: flex-end;">
            
            <span style="font-size: 11px; color: var(--blue-700, #1d4ed8); font-weight: 600; margin-bottom: 6px; display: block;">
              <?= $d['val'] ?>
            </span>
            
            <div class="bar" style="width: 100%; max-width: 32px; height: <?= $h ?>%; min-height: <?= $d['val'] > 0 ? '8px' : '2px' ?>; background: <?= $h == 100 ? 'var(--blue-500, #3b82f6)' : 'var(--blue-200, #bfdbfe)' ?>; border-radius: 4px 4px 0 0; transition: height 0.4s ease; box-shadow: inset 0 1px 0 rgba(255,255,255,0.15);"></div>
            
            <span class="bar-label" style="font-size: 11.5px; color: var(--gray-500, #6b7280); margin-top: 8px; font-weight: 500; display: block;">
              <?= $d['bulan'] ?>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
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
    <div class="donut-wrap" style="position: relative; display: inline-block; width: 150px; height: 150px; margin: 0 auto 15px;">
      
      <svg class="donut-svg" viewBox="0 0 42 42" style="transform: rotate(-90deg); width: 100%; height: 100%;">
        <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#e8f4fd" stroke-width="5"/>
        
        <?php 
        $accumulated_percentage = 0; 
        foreach ($status_counts as $status_name => $count): 
            if ($count === 0) continue; // Abaikan status yang bernilai 0 agar tidak merusak lingkaran
            
            $percentage = ($count / $total_tugas_untuk_pembagian) * 100;
            $dash_array = sprintf("%.2f %.2f", $percentage, 100 - $percentage);
            $dash_offset = 25 - $accumulated_percentage;
            $accumulated_percentage += $percentage;
        ?>
          <circle cx="21" cy="21" r="15.915" fill="transparent" 
                  stroke="<?= $status_colors[$status_name] ?>" stroke-width="5"
                  stroke-dasharray="<?= $dash_array ?>" stroke-dashoffset="<?= $dash_offset ?>" 
                  style="transition: stroke-dasharray 0.6s"/>
        <?php endforeach; ?>
      </svg>
      
      <div class="donut-center" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
        <div class="donut-value" style="font-size: 22px; font-weight: bold; color: var(--blue-900);"><?= $total_tugas_kurir ?></div>
        <div class="donut-desc" style="font-size: 11px; color: var(--gray-500);">Total Tugas</div>
      </div>
    </div>
    
    <div style="text-align:left; display: flex; flex-direction: column; gap: 6px; margin-top: 5px;">
      <?php foreach ($status_counts as $status_name => $count): ?>
        <div class="legend-item" style="display: flex; align-items: center; justify-content: space-between; padding: 2px 0;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <div class="legend-dot" style="width: 10px; height: 10px; border-radius: 50%; background: <?= $status_colors[$status_name] ?>"></div>
            <span style="font-size:13px"><?= $status_name ?></span>
          </div>
          <span style="font-weight:600; color:var(--blue-900)"><?= $count ?></span>
        </div>
      <?php endforeach; ?>
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
