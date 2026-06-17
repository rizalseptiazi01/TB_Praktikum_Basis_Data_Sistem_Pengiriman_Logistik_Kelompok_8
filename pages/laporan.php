<?php
if (!function_exists('rupiah')) {
    function rupiah($angka) {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

// Menentukan rentang bulan ini untuk query (default tahun berjalan)
$tahun_ini = date('Y');
// Menangkap bulan secara dinamis dari filter
$bulan_ini = isset($_GET['filter_bulan']) ? $_GET['filter_bulan'] : date('m');

$total_pengiriman_bulan = $pdo ? $pdo->query(
    "SELECT COUNT(*) FROM pengiriman WHERE MONTH(tgl_pengiriman) = '$bulan_ini' AND YEAR(tgl_pengiriman) = '$tahun_ini'"
)->fetchColumn() : 0; 

// Pengiriman sukses & gagal dibaca lewat JOIN tabel penugasan_kurir
$pengiriman_sukses = $pdo ? $pdo->query(
    "SELECT COUNT(DISTINCT p.id_pengiriman) FROM pengiriman p 
     JOIN penugasan_kurir pk ON p.id_pengiriman = pk.id_pengiriman 
     WHERE pk.status_tugas = 'delivered' AND MONTH(p.tgl_pengiriman) = '$bulan_ini' AND YEAR(p.tgl_pengiriman) = '$tahun_ini'"
)->fetchColumn() : 0; // KITA UBAH JADI 0 JIKA DB KOSONG

$pengiriman_gagal = $pdo ? $pdo->query(
    "SELECT COUNT(DISTINCT p.id_pengiriman) FROM pengiriman p 
     JOIN penugasan_kurir pk ON p.id_pengiriman = pk.id_pengiriman 
     WHERE pk.status_tugas = 'failed' AND MONTH(p.tgl_pengiriman) = '$bulan_ini' AND YEAR(p.tgl_pengiriman) = '$tahun_ini'"
)->fetchColumn() : 0; 
$success_rate = $total_pengiriman_bulan > 0 ? round(($pengiriman_sukses / $total_pengiriman_bulan) * 100, 1) : 0;

// Rata-rata biaya kirim
if ($pdo) {
    $biaya_query = $pdo->query(
        "SELECT SUM(total_biaya) as total_duit, COUNT(*) as total_kirim 
         FROM pengiriman 
         WHERE MONTH(tgl_pengiriman) = '$bulan_ini' AND YEAR(tgl_pengiriman) = '$tahun_ini'"
    )->fetch();
    
    $rata_biaya = $biaya_query['total_kirim'] > 0 ? ($biaya_query['total_duit'] / $biaya_query['total_kirim']) : 0;
} else {
    $rata_biaya = 0;
}


if ($pdo) {
    $data_bulanan = [];
    $query_bulan = $pdo->query(
        "SELECT MONTHNAME(p.tgl_pengiriman) as bln, MONTH(p.tgl_pengiriman) as bln_num,
                COUNT(p.id_pengiriman) as total, 
                SUM(p.total_biaya) as revenue,
                SUM(CASE WHEN pk.status_tugas = 'delivered' THEN 1 ELSE 0 END) as sukses
         FROM pengiriman p
         LEFT JOIN penugasan_kurir pk ON p.id_pengiriman = pk.id_pengiriman
         WHERE YEAR(p.tgl_pengiriman) = '$tahun_ini'
         GROUP BY MONTH(p.tgl_pengiriman) ORDER BY MONTH(p.tgl_pengiriman) ASC"
    )->fetchAll();

    foreach ($query_bulan as $qb) {
        $nama_bln = substr($qb['bln'], 0, 3);
        $data_bulanan[] = [
            'bulan' => $nama_bln,
            'pengiriman' => $qb['total'],
            'pendapatan' => $qb['revenue'] ?? 0,
            'sukses' => $qb['sukses']
        ];
    }
} else {
    $data_bulanan = [
        ['bulan'=>'Jan','pengiriman'=>85,'pendapatan'=>10625000,'sukses'=>78],
        ['bulan'=>'Feb','pengiriman'=>72,'pendapatan'=>9000000,'sukses'=>65],
        ['bulan'=>'Mar','pengiriman'=>95,'pendapatan'=>11875000,'sukses'=>88],
        ['bulan'=>'Apr','pengiriman'=>110,'pendapatan'=>13750000,'sukses'=>102],
        ['bulan'=>'Mei','pengiriman'=>412,'pendapatan'=>51500000,'sukses'=>378],
    ];
}


if ($pdo) {
    $kota_top = [];
    $query_kota = $pdo->query(
        "SELECT kota_tujuan, COUNT(*) as jumlah 
         FROM pengiriman 
         GROUP BY kota_tujuan ORDER BY jumlah DESC LIMIT 5"
    )->fetchAll();
    
    $total_all_kirim = array_sum(array_column($query_kota, 'jumlah'));
    foreach ($query_kota as $qk) {
        $kota_top[] = [
            'kota' => $qk['kota_tujuan'],
            'count' => $qk['jumlah'],
            'pct' => $total_all_kirim > 0 ? round(($qk['jumlah'] / $total_all_kirim) * 100) : 0
        ];
    }
} else {
    $kota_top = [
        ['kota'=>'Surabaya','count'=>145,'pct'=>35],
        ['kota'=>'Bandung','count'=>98,'pct'=>24],
        ['kota'=>'Medan','count'=>76,'pct'=>18],
        ['kota'=>'Yogyakarta','count'=>65,'pct'=>16],
        ['kota'=>'Makassar','count'=>28,'pct'=>7],
    ];
}
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="page-title">📈 Laporan & Analitik</div>
    <div class="page-subtitle">Ringkasan performa sistem pengiriman real-time</div>
  </div>
  <div style="display:flex;gap:10px">
    
    <form method="GET" action="index.php" style="margin:0; display:flex;">
      <input type="hidden" name="page" value="laporan">
      <select name="filter_bulan" class="filter-select" onchange="this.form.submit()" style="cursor:pointer;">
        <?php 
        for ($m = 1; $m <= 12; $m++) {
            $value_bulan = str_pad($m, 2, '0', STR_PAD_LEFT);
            $nama_bulan_panjang = date('F Y', mktime(0, 0, 0, $m, 1, $tahun_ini));
            $selected = ($value_bulan == $bulan_ini) ? 'selected' : '';
            echo "<option value='{$value_bulan}' {$selected}>{$nama_bulan_panjang}</option>";
        }
        ?>
      </select>
    </form>

    <button class="btn btn-secondary" onclick="window.print()">📥 Cetak Halaman</button>
  </div>
</div>

<div class="stats-grid" style="margin-bottom:24px">
  <div class="stat-card">
    <div class="stat-icon-wrap blue">📦</div>
    <div class="stat-info">
      <div class="stat-value"><?= number_format($total_pengiriman_bulan) ?></div>
      <div class="stat-label">Total Pengiriman (Bulan Ini)</div>
      <div class="stat-change up">▲ Berdasarkan Pengiriman</div>
    </div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon-wrap green">✅</div>
    <div class="stat-info">
      <div class="stat-value"><?= $success_rate ?>%</div>
      <div class="stat-label">Tingkat Keberhasilan</div>
      <div class="stat-change up">▲ Rasio Pengiriman</div>
    </div>
  </div>
  <div class="stat-card amber">
    <div class="stat-icon-wrap amber">💰</div>
    <div class="stat-info">
      <div class="stat-value"><?= rupiah($rata_biaya) ?></div>
      <div class="stat-label">Rata-rata Biaya Kirim</div>
      <div class="stat-change down">▼ Rerata Nilai</div>
    </div>
  </div>
  <div class="stat-card red">
    <div class="stat-icon-wrap red">❌</div>
    <div class="stat-info">
      <div class="stat-value"><?= $pengiriman_gagal ?></div>
      <div class="stat-label">Pengiriman Gagal</div>
      <div class="stat-change up"></div>
    </div>
  </div>
</div>

<div class="dashboard-grid" style="margin-bottom:20px">
  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">📊 Rekap Bulanan Terkini</div>
        <div class="card-subtitle">Jumlah pengiriman dan pendapatan per bulan</div>
      </div>
    </div>
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Bulan</th>
            <th>Total Pengiriman</th>
            <th>Pengiriman Sukses</th>
            <th>Pendapatan</th>
            <th>Success Rate</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data_bulanan as $d): ?>
          <?php $rate = $d['pengiriman'] > 0 ? round(($d['sukses']/$d['pengiriman'])*100,1) : 0; ?>
          <tr>
            <td><strong><?= $d['bulan'] ?></strong></td>
            <td><?= number_format($d['pengiriman']) ?></td>
            <td><?= number_format($d['sukses']) ?></td>
            <td style="color:var(--blue-700);font-weight:600"><?= rupiah($d['pendapatan']) ?></td>
            <td>
              <div style="display:flex;align-items:center;gap:8px">
                <div style="flex:1;height:6px;background:var(--gray-100);border-radius:3px;overflow:hidden">
                  <div style="width:<?= $rate ?>%;height:100%;background:var(--success);border-radius:3px"></div>
                </div>
                <span style="font-size:12px;font-weight:600;color:var(--success)"><?= $rate ?>%</span>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title">🏙️ Kota Tujuan Populer</div>
    </div>
    <div class="card-body">
      <?php foreach ($kota_top as $i => $k): ?>
      <div style="margin-bottom:16px">
        <div style="display:flex;justify-content:space-between;margin-bottom:5px">
          <div style="display:flex;align-items:center;gap:8px">
            <span style="width:22px;height:22px;background:var(--blue-600);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0"><?= $i+1 ?></span>
            <span style="font-weight:600;color:var(--blue-900)"><?= htmlspecialchars($k['kota'] ?? '-') ?></span>
          </div>
          <span style="font-size:12.5px;color:var(--gray-500)"><?= $k['count'] ?> kiriman</span>
        </div>
        <div style="height:7px;background:var(--blue-50);border-radius:4px;overflow:hidden">
          <div style="width:<?= $k['pct'] ?>%;height:100%;background:linear-gradient(90deg,var(--blue-400),var(--blue-600));border-radius:4px;transition:width 0.6s ease"></div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if(empty($kota_top)): ?>
         <p style="text-align:center; color:var(--gray-400); margin-top:30px;">Belum ada data rute pengiriman.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
@media print {
  /* 1. Sembunyikan Topbar dan Sidebar saat dicetak */
  .topbar, 
  #topbar, 
  .sidebar, 
  #sidebar {
    display: none !important;
  }

  /* 2. Sembunyikan elemen tombol cetak dan form filter dropdown */
  .btn, 
  .btn-secondary, 
  .filter-select, 
  form {
    display: none !important;
  }

  /* 3. Paksa area konten utama bergeser penuh ke kiri (margin 0) agar tidak kepotong */
  .main-content, 
  .main, 
  #main-wrapper,
  body {
    margin: 0 !important;
    padding: 0 !important;
    left: 0 !important;
    width: 100% !important;
  }

  /* 4. Opsional: Optimasi bayangan card agar warnanya solid & hemat tinta printer */
  .card, .stat-card {
    border: 1px solid #e2e8f0 !important;
    box-shadow: none !important;
    background: #ffffff !important;
    page-break-inside: avoid; /* Mencegah card kepotong di tengah halaman */
  }
}
</style>
