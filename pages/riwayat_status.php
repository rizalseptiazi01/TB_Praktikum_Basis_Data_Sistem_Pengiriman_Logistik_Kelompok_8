<?php
// === 1. LOGIKA PHP: TAMBAH, UPDATE, & HAPUS RIWAYAT STATUS ===
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($pdo) {
        try {
            // Aksi: Tambah Status Baru
            if ($_POST['action'] === 'add') {
                $stmt = $pdo->prepare("INSERT INTO riwayat_status (id_riwayat, id_pengiriman, id_status, tgl_status_berubah, lokasi_status, catatan_status) 
                                       VALUES (:id_riwayat, :id_pengiriman, :id_status, :tgl_status_berubah, :lokasi_status, :catatan_status)");
                
                $waktu_status = !empty($_POST['tgl_status_berubah']) ? str_replace('T', ' ', $_POST['tgl_status_berubah']) : date('Y-m-d H:i:s');
                $stmt->execute([
                    ':id_riwayat'          => $_POST['id_riwayat'],
                    ':id_pengiriman'       => $_POST['id_pengiriman'],
                    ':id_status'           => $_POST['id_status'],
                    ':tgl_status_berubah'  => $waktu_status,
                    ':lokasi_status'       => !empty($_POST['lokasi_status']) ? $_POST['lokasi_status'] : null,
                    ':catatan_status'      => !empty($_POST['catatan_status']) ? $_POST['catatan_status'] : null
                ]);
                $msg = '<div class="alert alert-success">✅ Riwayat status pengiriman baru berhasil ditambahkan!</div>';

            // Aksi: Perbarui / Edit Status Lama
            } elseif ($_POST['action'] === 'update') {
                $stmt = $pdo->prepare("UPDATE riwayat_status SET 
                                       id_status = :id_status, 
                                       tgl_status_berubah = :tgl_status_berubah, 
                                       lokasi_status = :lokasi_status, 
                                       catatan_status = :catatan_status 
                                       WHERE id_riwayat = :id_riwayat");
                
                $waktu_status = !empty($_POST['tgl_status_berubah']) ? str_replace('T', ' ', $_POST['tgl_status_berubah']) : date('Y-m-d H:i:s');
                $stmt->execute([
                    ':id_status'           => $_POST['id_status'],
                    ':tgl_status_berubah'  => $waktu_status,
                    ':lokasi_status'       => $_POST['lokasi_status'],
                    ':catatan_status'      => $_POST['catatan_status'],
                    ':id_riwayat'          => $_POST['id_riwayat']
                ]);
                $msg = '<div class="alert alert-success">✅ Data riwayat status berhasil diperbarui!</div>';

            // Aksi: Hapus Baris Status
            } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
                $stmt = $pdo->prepare("DELETE FROM riwayat_status WHERE id_riwayat = :id_riwayat");
                $stmt->execute([':id_riwayat' => $_POST['id']]);
                $msg = '<div class="alert alert-success">✅ Data riwayat status berhasil dihapus!</div>';
            }
        } catch (PDOException $e) {
            $msg = '<div class="alert alert-danger">❌ Gagal memproses data riwayat: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}

// === 2. AMBIL DATA DARI DATABASE ===
$riwayat_list = [];
if ($pdo) {
    try {
        $riwayat_list = $pdo->query(
            "SELECT rs.*, COALESCE(s.nama_status, rs.id_status) AS nama_status 
             FROM riwayat_status rs 
             LEFT JOIN status s ON rs.id_status = s.id_status 
             ORDER BY rs.tgl_status_berubah DESC"
        )->fetchAll();
    } catch (PDOException $e) {
        // Fallback data simulasi jika koneksi atau tabel bermasalah
        $riwayat_list = [
            ['id_riwayat'=>'RWY-00001','id_pengiriman'=>'PGM-0001','id_status'=>'delivered','nama_status'=>'Terkirim','tgl_status_berubah'=>'2025-05-15 17:30:00','lokasi_status'=>'Surabaya','catatan_status'=>'Paket diterima oleh penerima'],
            ['id_riwayat'=>'RWY-00002','id_pengiriman'=>'PGM-0001','id_status'=>'in_transit','nama_status'=>'Dalam Perjalanan','tgl_status_berubah'=>'2025-05-15 10:00:00','lokasi_status'=>'Semarang','catatan_status'=>'Paket melewati kota transit Semarang']
        ];
    }
}

$status_icons = ['Terkirim'=>'✅','Dalam Perjalanan'=>'🚚','Pickup'=>'📦','Menunggu'=>'⏳','Gagal'=>'❌'];
$status_colors = ['Terkirim'=>'badge-green','Dalam Perjalanan'=>'badge-cyan','Pickup'=>'badge-blue','Menunggu'=>'badge-amber','Gagal'=>'badge-red'];
?>

<?= $msg ?>

<div class="page-header">
  <div class="page-header-left">
    <div class="page-title">🕐 Riwayat Status Pengiriman</div>
    <div class="page-subtitle">Lacak seluruh perubahan status logistik paket</div>
  </div>
  <button class="btn btn-primary" onclick="openTambahRiwayatModal()">➕ Tambah Status</button>
</div>

<div class="card" style="margin-bottom:20px">
  <div class="card-body" style="display:flex;align-items:center;gap:14px;flex-wrap:wrap">
    <div style="font-size:18px">🔍</div>
    <div style="flex:1;min-width:200px">
      <div style="font-weight:600;color:var(--blue-900);margin-bottom:2px">Lacak Pengiriman</div>
      <div style="font-size:12px;color:var(--gray-400)">Masukkan ID pengiriman untuk melihat riwayat lengkap</div>
    </div>
    <div style="display:flex;gap:10px;flex:1;min-width:280px">
      <input type="text" id="trackInput" class="form-control" placeholder="Contoh: PGM-0001" style="flex:1">
      <button class="btn btn-primary" onclick="trackShipment()">Lacak →</button>
    </div>
  </div>
</div>

<div id="trackResult" style="display:none;margin-bottom:20px">
  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">📍 Riwayat Perjalanan: <span id="trackId"></span></div>
        <div class="card-subtitle">Timeline runtutan posisi paket</div>
      </div>
      <button class="btn btn-secondary btn-sm" onclick="document.getElementById('trackResult').style.display='none'">✕ Tutup</button>
    </div>
    <div class="card-body">
      <div id="trackTimeline" class="timeline"></div>
    </div>
  </div>
</div>

<div class="filter-bar">
  <div class="filter-search">
    <span>🔍</span>
    <input type="text" id="searchRWY" placeholder="Cari ID pengiriman, lokasi..." oninput="filterRWY()">
  </div>
  <select class="filter-select" id="filterStatusRWY" onchange="filterRWY()">
    <option value="">Semua Status</option>
    <option>Terkirim</option>
    <option>Dalam Perjalanan</option>
    <option>Pickup</option>
    <option>Menunggu</option>
    <option>Gagal</option>
  </select>
</div>

<div class="card">
  <div class="table-wrapper">
    <table id="rwyTable">
      <thead>
        <tr>
          <th>ID Riwayat</th>
          <th>ID Pengiriman</th>
          <th>Status</th>
          <th>Waktu Perubahan</th>
          <th>Lokasi</th>
          <th>Catatan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($riwayat_list as $row): ?>
        <?php $stat = $row['nama_status'] ?? 'Menunggu'; ?>
        <tr>
          <td><strong><?= htmlspecialchars($row['id_riwayat']) ?></strong></td>
          <td>
            <span style="background:var(--blue-50);color:var(--blue-700);padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">
              <?= htmlspecialchars($row['id_pengiriman']) ?>
            </span>
          </td>
          <td>
            <span class="badge <?= $status_colors[$stat] ?? 'badge-gray' ?>">
              <?= ($status_icons[$stat] ?? '📌') . ' ' . htmlspecialchars($stat) ?>
            </span>
          </td>
          <td>
            <div style="font-size:13px"><?= date('d/m/Y', strtotime($row['tgl_status_berubah'])) ?></div>
            <div style="font-size:11.5px;color:var(--gray-400)"><?= date('H:i:s', strtotime($row['tgl_status_berubah'])) ?></div>
          </td>
          <td>📍 <?= htmlspecialchars($row['lokasi_status'] ?? '-') ?></td>
          <td style="max-width:200px">
            <div style="font-size:12.5px;color:var(--gray-500);white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="<?= htmlspecialchars($row['catatan_status'] ?? '') ?>">
              <?= htmlspecialchars($row['catatan_status'] ?? '-') ?>
            </div>
          </td>
          <td>
            <div style="display:flex;gap:5px">
              <button class="btn btn-secondary btn-sm" title="Edit" 
                      onclick="bukaEditRiwayat(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)">✏️</button>
              
              <form method="POST" style="display:inline" onsubmit="return confirm('Hapus baris riwayat status ini?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $row['id_riwayat'] ?>">
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

<div class="modal-overlay" id="modalRiwayat" style="display:none">
  <div class="modal" style="max-width:550px">
    <div class="modal-header">
      <div class="modal-title" id="modalTitleRiwayat">🕐 Tambah Riwayat Status Baru</div>
      <button class="modal-close" onclick="closeModal('modalRiwayat')">✕</button>
    </div>
    <form method="POST" id="formRiwayat">
      <input type="hidden" name="action" id="formActionRiwayat" value="add">
      <div class="modal-body">
        <div class="form-grid form-grid-2">
          <div class="form-group">
            <label class="form-label">ID Riwayat <span>*</span></label>
            <input type="text" name="id_riwayat" id="rwy_id_riwayat" class="form-control" placeholder="RWY-xxxxx" required>
          </div>
          <div class="form-group">
            <label class="form-label">ID Pengiriman <span>*</span></label>
            <select name="id_pengiriman" id="rwy_id_pengiriman" class="form-control" required>
              <option value="">-- Pilih Transaksi --</option>
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
            <label class="form-label">Status Logistik <span>*</span></label>
            <select name="id_status" id="rwy_id_status" class="form-control" required>
              <option value="">-- Pilih Status --</option>
              <?php 
              $berhasil_muat = false;
              if ($pdo) {
                  try {
                      $stats = $pdo->query("SELECT id_status, nama_status FROM status ORDER BY id_status")->fetchAll();
                      if (!empty($stats)) {
                          foreach ($stats as $s) {
                              echo '<option value="'.htmlspecialchars($s['id_status']).'">'.htmlspecialchars($s['nama_status']).'</option>';
                          }
                          $berhasil_muat = true;
                      }
                  } catch (PDOException $e) {}
              }
              if (!$berhasil_muat) {
                  $opsi_standar = ['pending'=>'Menunggu', 'pickup'=>'Pickup', 'in_transit'=>'Dalam Perjalanan', 'delivered'=>'Terkirim', 'failed'=>'Gagal'];
                  foreach ($opsi_standar as $key => $val) {
                      echo '<option value="'.htmlspecialchars($key).'">'.htmlspecialchars($val).'</option>';
                  }
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Waktu Status Perubahan</label>
            <input type="datetime-local" name="tgl_status_berubah" id="rwy_tgl" class="form-control">
          </div>
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Lokasi Saat Ini</label>
            <input type="text" name="lokasi_status" id="rwy_lokasi" class="form-control" placeholder="Contoh: Gudang Transit Semarang">
          </div>
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Catatan Tambahan</label>
            <textarea name="catatan_status" id="rwy_catatan" class="form-control" placeholder="Keterangan detail posisi paket atau penerima..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalRiwayat')">Batal</button>
        <button type="submit" class="btn btn-primary">💾 Simpan Status</button>
      </div>
    </form>
  </div>
</div>

<script>
// Passing data PHP ke array javascript untuk fitur lacak timeline
const riwayatData = <?= json_encode($riwayat_list) ?>;

function trackShipment() {
  const id = document.getElementById('trackInput').value.trim().toUpperCase();
  if (!id) return;
  const filtered = riwayatData.filter(r => r.id_pengiriman.toUpperCase() === id);
  if (!filtered.length) {
    alert('Tidak ada riwayat pelacakan untuk nomor ID pengiriman: ' + id);
    return;
  }
  document.getElementById('trackId').textContent = id;
  const icons = {'Terkirim':'✅','Dalam Perjalanan':'🚚','Pickup':'📦','Menunggu':'⏳','Gagal':'❌'};
  
  let html = '';
  filtered.forEach(r => {
    const s = r.nama_status || 'Menunggu';
    const d = new Date(r.tgl_status_berubah);
    const formattedDate = d.toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'});
    const formattedTime = d.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
    
    html += `<div class="timeline-item" style="display:flex;gap:15px;margin-bottom:15px;position:relative">
      <div class="timeline-dot" style="font-size:20px;z-index:2">${icons[s] || '📌'}</div>
      <div class="timeline-content" style="background:var(--blue-50);padding:10px 15px;border-radius:8px;flex:1">
        <div class="timeline-title" style="font-weight:700;color:var(--blue-900)">${s} — ${r.lokasi_status ?? '-'}</div>
        <div class="timeline-desc" style="font-size:12px;color:var(--gray-500);margin-top:2px">${formattedDate} pukul ${formattedTime} WIB · <em>${r.catatan_status || 'Tidak ada catatan'}</em></div>
      </div>
    </div>`;
  });
  document.getElementById('trackTimeline').innerHTML = html;
  document.getElementById('trackResult').style.display = 'block';
  document.getElementById('trackResult').scrollIntoView({behavior:'smooth'});
}

function openTambahRiwayatModal() {
  document.getElementById('modalTitleRiwayat').innerText = "🕐 Tambah Riwayat Status Baru";
  document.getElementById('formActionRiwayat').value = "add";
  document.getElementById('formRiwayat').reset();
  
  // Auto-generate ID Riwayat unik instan agar tidak capek mengetik manual
  document.getElementById('rwy_id_riwayat').value = 'RWY-' + Date.now().toString().slice(-6);
  document.getElementById('rwy_id_riwayat').readOnly = false;
  document.getElementById('rwy_id_riwayat').style.background = '#ffffff';
  
  // Set waktu otomatis ke jam sekarang di komputer
  const sekarang = new Date();
  sekarang.setMinutes(sekarang.getMinutes() - sekarang.getTimezoneOffset());
  document.getElementById('rwy_tgl').value = sekarang.toISOString().slice(0,16);
  
  openModal('modalRiwayat');
}

function bukaEditRiwayat(data) {
  document.getElementById('modalTitleRiwayat').innerText = "✏️ Edit Riwayat Status Paket";
  document.getElementById('formActionRiwayat').value = "update";
  
  // Kunci ID Riwayat karena merupakan Primary Key unik
  document.getElementById('rwy_id_riwayat').value = data.id_riwayat;
  document.getElementById('rwy_id_riwayat').readOnly = true;
  document.getElementById('rwy_id_riwayat').style.background = 'var(--blue-50)';
  
  document.getElementById('rwy_id_pengiriman').value = data.id_pengiriman;
  document.getElementById('rwy_id_status').value = data.id_status ? data.id_status : data.nama_status;
  
  if(data.tgl_status_berubah) {
    document.getElementById('rwy_tgl').value = data.tgl_status_berubah.replace(' ', 'T').slice(0,16);
  }
  
  document.getElementById('rwy_lokasi').value = data.lokasi_status;
  document.getElementById('rwy_catatan').value = data.catatan_status;
  
  openModal('modalRiwayat');
}

function filterRWY() {
  const q = document.getElementById('searchRWY').value.toLowerCase();
  const s = document.getElementById('filterStatusRWY').value.toLowerCase();
  document.querySelectorAll('#rwyTable tbody tr').forEach(tr => {
    const text = tr.innerText.toLowerCase();
    tr.style.display = (text.includes(q) && (!s || text.includes(s))) ? '' : 'none';
  });
}
</script>