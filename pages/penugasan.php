<?php
// === LOGIKA PHP: TAMBAH, UPDATE, & HAPUS PENUGASAN KURIR ===
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($pdo) {
        try {
            if ($_POST['action'] === 'add') {
                $stmt = $pdo->prepare("INSERT INTO penugasan_kurir (id_pengiriman, id_kurir, urutan_kurir, tgl_mulai_tugas, kota_asal_rute, kota_tujuan_rute, jarak_km, biaya_kurir, status_tugas, catatan_tugas) 
                                       VALUES (:id_pengiriman, :id_kurir, :urutan_kurir, :tgl_mulai_tugas, :kota_asal_rute, :kota_tujuan_rute, :jarak_km, :biaya_kurir, :status_tugas, :catatan_tugas)");
                
                $tgl_mulai = !empty($_POST['tgl_mulai_tugas']) ? $_POST['tgl_mulai_tugas'] : date('Y-m-d H:i:s');
                $stmt->execute([
                    ':id_pengiriman'     => $_POST['id_pengiriman'],
                    ':id_kurir'          => $_POST['id_kurir'],
                    ':urutan_kurir'      => !empty($_POST['urutan_kurir']) ? $_POST['urutan_kurir'] : 1,
                    ':tgl_mulai_tugas'   => $tgl_mulai,
                    ':kota_asal_rute'    => !empty($_POST['kota_asal_rute']) ? $_POST['kota_asal_rute'] : null,
                    ':kota_tujuan_rute'  => !empty($_POST['kota_tujuan_rute']) ? $_POST['kota_tujuan_rute'] : null,
                    ':jarak_km'          => !empty($_POST['jarak_km']) ? $_POST['jarak_km'] : 0,
                    ':biaya_kurir'       => !empty($_POST['biaya_kurir']) ? $_POST['biaya_kurir'] : 0,
                    ':status_tugas'      => !empty($_POST['status_tugas']) ? $_POST['status_tugas'] : 'pending',
                    ':catatan_tugas'     => !empty($_POST['catatan_tugas']) ? $_POST['catatan_tugas'] : null
                ]);
                $msg = '<div class="alert alert-success">✅ Penugasan kurir baru berhasil disimpan!</div>';

            } elseif ($_POST['action'] === 'update') {
                $stmt = $pdo->prepare("UPDATE penugasan_kurir SET 
                                       id_kurir = :id_kurir, 
                                       urutan_kurir = :urutan_kurir, 
                                       tgl_mulai_tugas = :tgl_mulai_tugas, 
                                       tgl_selesai_tugas = :tgl_selesai_tugas,
                                       kota_asal_rute = :kota_asal_rute, 
                                       kota_tujuan_rute = :kota_tujuan_rute, 
                                       jarak_km = :jarak_km, 
                                       biaya_kurir = :biaya_kurir, 
                                       status_tugas = :status_tugas,
                                       catatan_tugas = :catatan_tugas
                                       WHERE id_pengiriman = :id_pengiriman");
                
                $tgl_selesai = !empty($_POST['tgl_selesai_tugas']) ? $_POST['tgl_selesai_tugas'] : null;
                $stmt->execute([
                    ':id_pengiriman'     => $_POST['id_pengiriman'],
                    ':id_kurir'          => $_POST['id_kurir'],
                    ':urutan_kurir'      => $_POST['urutan_kurir'],
                    ':tgl_mulai_tugas'   => $_POST['tgl_mulai_tugas'],
                    ':tgl_selesai_tugas' => $tgl_selesai,
                    ':kota_asal_rute'    => $_POST['kota_asal_rute'],
                    ':kota_tujuan_rute'  => $_POST['kota_tujuan_rute'],
                    ':jarak_km'          => $_POST['jarak_km'],
                    ':biaya_kurir'       => $_POST['biaya_kurir'],
                    ':status_tugas'      => $_POST['status_tugas'],
                    ':catatan_tugas'     => $_POST['catatan_tugas']
                ]);
                $msg = '<div class="alert alert-success">✅ Data penugasan kurir berhasil diperbarui!</div>';

            } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
                $stmt = $pdo->prepare("DELETE FROM penugasan_kurir WHERE id_pengiriman = :id_pengiriman");
                $stmt->execute([':id_pengiriman' => $_POST['id']]);
                $msg = '<div class="alert alert-success">✅ Data penugasan kurir berhasil dihapus!</div>';
            }
        } catch (PDOException $e) {
            $msg = '<div class="alert alert-danger">❌ Gagal memproses data penugasan: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}

// Ambil list data penugasan dari Database
$penugasan_list = [];
if ($pdo) {
    try {
        $penugasan_list = $pdo->query(
            "SELECT pk.*, k.nama_kurir, k.kendaraan FROM penugasan_kurir pk LEFT JOIN kurir k ON pk.id_kurir=k.id_kurir ORDER BY pk.tgl_mulai_tugas DESC"
        )->fetchAll();
    } catch (PDOException $e) {
        // Fallback data simulasi jika tabel belum terbuat
        $penugasan_list = [
            ['id_pengiriman'=>'PGM-0001','id_kurir'=>'KUR-001','nama_kurir'=>'Budi Kurniawan','urutan_kurir'=>1,'tgl_mulai_tugas'=>'2025-05-15 08:00:00','tgl_selesai_tugas'=>'2025-05-15 17:30:00','kota_asal_rute'=>'Jakarta','kota_tujuan_rute'=>'Surabaya','jarak_km'=>780,'biaya_kurir'=>250000,'status_tugas'=>'delivered','kendaraan'=>'Motor','catatan_tugas'=>''],
            ['id_pengiriman'=>'PGM-0002','id_kurir'=>'KUR-002','nama_kurir'=>'Dian Permana','urutan_kurir'=>1,'tgl_mulai_tugas'=>'2025-05-16 09:00:00','tgl_selesai_tugas'=>null,'kota_asal_rute'=>'Jakarta','kota_tujuan_rute'=>'Bandung','jarak_km'=>150,'biaya_kurir'=>85000,'status_tugas'=>'in_transit','kendaraan'=>'Motor','catatan_tugas'=>'']
        ];
    }
}
?>

<?= $msg ?>

<div class="page-header">
  <div class="page-header-left">
    <div class="page-title">📋 Penugasan Kurir</div>
    <div class="page-subtitle">Manajemen penugasan kurir per rute pengiriman</div>
  </div>
  <button class="btn btn-primary" onclick="openTambahPenugasanModal()">➕ Tambah Penugasan</button>
</div>

<div class="filter-bar">
  <div class="filter-search">
    <span>🔍</span>
    <input type="text" id="searchPT" placeholder="Cari ID pengiriman atau nama kurir..." oninput="filterPT()">
  </div>
  <select class="filter-select" id="filterStatPT" onchange="filterPT()">
    <option value="">Semua Status</option>
    <option value="pending">Pending</option>
    <option value="pickup">Pickup</option>
    <option value="in_transit">Dalam Perjalanan</option>
    <option value="delivered">Terkirim</option>
    <option value="failed">Gagal</option>
  </select>
</div>

<div class="card">
  <div class="table-wrapper">
    <table id="ptTable">
      <thead>
        <tr>
          <th>ID Pengiriman</th>
          <th>Kurir</th>
          <th>Urutan</th>
          <th>Rute</th>
          <th>Jarak (km)</th>
          <th>Mulai Tugas</th>
          <th>Selesai Tugas</th>
          <th>Biaya Kurir</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($penugasan_list as $row): ?>
        <tr data-status="<?= htmlspecialchars($row['status_tugas'] ?? 'pending') ?>">
          <td><strong style="color:var(--blue-600)"><?= htmlspecialchars($row['id_pengiriman']) ?></strong></td>
          <td>
            <div style="display:flex;align-items:center;gap:8px">
              <div style="width:30px;height:30px;background:var(--blue-100);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--blue-700);font-size:12px;flex-shrink:0">
                <?= strtoupper(substr(($row['nama_kurir'] ?? 'K'),0,1)) ?>
              </div>
              <div>
                <div style="font-weight:600;color:var(--blue-900);font-size:13px"><?= htmlspecialchars($row['nama_kurir'] ?? 'Belum Ditunjuk') ?></div>
                <div style="font-size:11px;color:var(--gray-400)"><?= htmlspecialchars($row['id_kurir'] ?? '-') ?></div>
              </div>
            </div>
          </td>
          <td style="text-align:center">
            <div style="width:28px;height:28px;background:var(--blue-700);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;margin:0 auto">
              <?= $row['urutan_kurir'] ?? 1 ?>
            </div>
          </td>
          <td>
            <div style="display:flex;align-items:center;gap:6px;font-size:13px">
              <span style="background:var(--blue-50);padding:2px 8px;border-radius:4px;color:var(--blue-700);font-weight:500"><?= htmlspecialchars($row['kota_asal_rute'] ?? '-') ?></span>
              <span style="color:var(--gray-300)">→</span>
              <span style="background:var(--success-soft);padding:2px 8px;border-radius:4px;color:#065f46;font-weight:500"><?= htmlspecialchars($row['kota_tujuan_rute'] ?? '-') ?></span>
            </div>
          </td>
          <td><?= number_format(($row['jarak_km'] ?? 0.0),1) ?> km</td>
          <td>
            <?= !empty($row['tgl_mulai_tugas']) ? '<div style="font-size:12.5px">' . date('d/m/Y', strtotime($row['tgl_mulai_tugas'])) . '</div><div style="font-size:11px;color:var(--gray-400)">' . date('H:i', strtotime($row['tgl_mulai_tugas'])) . '</div>' : '<span style="color:var(--gray-300)">Belum mulai</span>' ?>
          </td>
          <td>
            <?= !empty($row['tgl_selesai_tugas']) ? '<div style="font-size:12.5px">' . date('d/m/Y', strtotime($row['tgl_selesai_tugas'])) . '</div><div style="font-size:11px;color:var(--gray-400)">' . date('H:i', strtotime($row['tgl_selesai_tugas'])) . '</div>' : '<span style="color:var(--gray-300)">—</span>' ?>
          </td>
          <td style="font-weight:700;color:var(--blue-700)"><?= rupiah($row['biaya_kurir'] ?? 0) ?></td>
          <td><?= badge_status($row['status_tugas'] ?? 'pending') ?></td>
          <td>
            <div style="display:flex;gap:5px">
              <button class="btn btn-secondary btn-sm" title="Edit" onclick="bukaEditPenugasan(<?= htmlspecialchars(json_encode($row)) ?>)">✏️</button>
              <form method="POST" style="display:inline" onsubmit="return confirm('Hapus penugasan untuk transaksi <?= $row['id_pengiriman'] ?>?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $row['id_pengiriman'] ?>">
                <button class="btn btn-danger btn-sm" type="submit" title="Hapus">🗑️</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal-overlay" id="modalPenugasan" style="display:none">
  <div class="modal" style="max-width:600px">
    <div class="modal-header">
      <div class="modal-title" id="modalTitlePenugasan">📋 Tambah Penugasan Kurir</div>
      <button class="modal-close" onclick="closeModal('modalPenugasan')">✕</button>
    </div>
    <form method="POST" id="formPenugasan">
      <input type="hidden" name="action" id="formActionPenugasan" value="add">
      <div class="modal-body">
        <div class="form-grid form-grid-2">
          <div class="form-group">
            <label class="form-label">ID Pengiriman <span>*</span></label>
            <select name="id_pengiriman" id="pt_id_pengiriman" class="form-control" required>
              <option value="">-- Pilih Pengiriman --</option>
              <?php 
              if($pdo):
                try {
                  $pgms = $pdo->query("SELECT id_pengiriman FROM pengiriman ORDER BY id_pengiriman DESC")->fetchAll();
                  foreach($pgms as $p) {
                    echo '<option value="'.$p['id_pengiriman'].'">'.$p['id_pengiriman'].'</option>';
                  }
                } catch(PDOException $e){}
              endif;
              ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Kurir <span>*</span></label>
            <select name="id_kurir" id="pt_id_kurir" class="form-control" required>
              <option value="">-- Pilih Kurir --</option>
              <?php 
              if($pdo):
                try {
                  $kurs = $pdo->query("SELECT id_kurir, nama_kurir FROM kurir WHERE status_kurir='aktif' ORDER BY nama_kurir")->fetchAll();
                  foreach($kurs as $k) {
                    echo '<option value="'.$k['id_kurir'].'">'.htmlspecialchars($k['nama_kurir']).'</option>';
                  }
                } catch(PDOException $e){}
              endif;
              ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Urutan Kurir</label>
            <input type="number" name="urutan_kurir" id="pt_urutan" class="form-control" value="1" min="1">
          </div>
          <div class="form-group">
            <label class="form-label">Biaya Kurir (Rp)</label>
            <input type="number" name="biaya_kurir" id="pt_biaya" class="form-control" placeholder="0">
          </div>
          <div class="form-group">
            <label class="form-label">Waktu Mulai Tugas</label>
            <input type="text" name="tgl_mulai_tugas" id="pt_tgl_mulai" class="form-control" placeholder="YYYY-MM-DD HH:MM:SS">
          </div>
          <div class="form-group">
            <label class="form-label">Waktu Selesai Tugas</label>
            <input type="text" name="tgl_selesai_tugas" id="pt_tgl_selesai" class="form-control" placeholder="YYYY-MM-DD HH:MM:SS (Kosongkan jika jalan)">
          </div>
          <div class="form-group">
            <label class="form-label">Kota Asal Rute</label>
            <input type="text" name="kota_asal_rute" id="pt_asal" class="form-control" placeholder="Jakarta">
          </div>
          <div class="form-group">
            <label class="form-label">Kota Tujuan Rute</label>
            <input type="text" name="kota_tujuan_rute" id="pt_tujuan" class="form-control" placeholder="Surabaya">
          </div>
          <div class="form-group">
            <label class="form-label">Jarak (km)</label>
            <input type="number" step="0.01" name="jarak_km" id="pt_jarak" class="form-control" placeholder="0">
          </div>
          <div class="form-group">
            <label class="form-label">Status Tugas</label>
            <select name="status_tugas" id="pt_status" class="form-control">
              <option value="pending">Pending</option>
              <option value="pickup">Pickup</option>
              <option value="in_transit">Dalam Perjalanan</option>
              <option value="delivered">Terkirim</option>
              <option value="failed">Gagal</option>
            </select>
          </div>
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Catatan Tugas</label>
            <textarea name="catatan_tugas" id="pt_catatan" class="form-control" placeholder="Catatan atau instruksi khusus..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalPenugasan')">Batal</button>
        <button type="submit" class="btn btn-primary">💾 Simpan Penugasan</button>
      </div>
    </form>
  </div>
</div>

<script>
function filterPT() {
  const q = document.getElementById('searchPT').value.toLowerCase();
  const s = document.getElementById('filterStatPT').value;
  document.querySelectorAll('#ptTable tbody tr').forEach(tr => {
    const text = tr.innerText.toLowerCase();
    const stat = tr.dataset.status;
    tr.style.display = (text.includes(q) && (!s || stat === s)) ? '' : 'none';
  });
}

function openTambahPenugasanModal() {
  document.getElementById('modalTitlePenugasan').innerText = "📋 Tambah Penugasan Kurir";
  document.getElementById('formActionPenugasan').value = "add";
  document.getElementById('pt_id_pengiriman').disabled = false;
  document.getElementById('formPenugasan').reset();
  
  // Set default waktu sekarang agar memudahkan pengisian
  const skrg = new Date().toISOString().slice(0, 19).replace('T', ' ');
  document.getElementById('pt_tgl_mulai').value = skrg;
  
  openModal('modalPenugasan');
}

function bukaEditPenugasan(data) {
  document.getElementById('modalTitlePenugasan').innerText = "✏️ Edit Tugas Kurir";
  document.getElementById('formActionPenugasan').value = "update";
  
  document.getElementById('pt_id_pengiriman').value = data.id_pengiriman;
  // Kunci ID Pengiriman agar tidak diganti saat mengedit rute berjalan
  document.getElementById('pt_id_pengiriman').disabled = false; 
  
  document.getElementById('pt_id_kurir').value = data.id_kurir;
  document.getElementById('pt_urutan').value = data.urutan_kurir;
  document.getElementById('pt_biaya').value = data.biaya_kurir;
  document.getElementById('pt_tgl_mulai').value = data.tgl_mulai_tugas;
  document.getElementById('pt_tgl_selesai').value = data.tgl_selesai_tugas ? data.tgl_selesai_tugas : '';
  document.getElementById('pt_asal').value = data.kota_asal_rute;
  document.getElementById('pt_tujuan').value = data.kota_tujuan_rute;
  document.getElementById('pt_jarak').value = data.jarak_km;
  document.getElementById('pt_status').value = data.status_tugas;
  document.getElementById('pt_catatan').value = data.catatan_tugas;
  
  openModal('modalPenugasan');
}

// Tambahan proteksi sebelum submit agar value disabled tetap terbawa masuk ke server POST
document.getElementById('formPenugasan').addEventListener('submit', function() {
    document.getElementById('pt_id_pengiriman').disabled = false;
});
</script>