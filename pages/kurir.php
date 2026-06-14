<?php

// TAMBAH, UPDATE, & HAPUS KURIR 
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($pdo) {
        try {
            if ($_POST['action'] === 'add') {
                $stmt = $pdo->prepare("INSERT INTO kurir (id_kurir, nama_kurir, no_telp, area_operasi, kendaraan, status_kurir, rating) 
                                       VALUES (:id_kurir, :nama_kurir, :no_telp, :area_operasi, :kendaraan, :status_kurir, 5.0)");
                
                $stmt->execute([
                    ':id_kurir' => $_POST['id_kurir'],
                    ':nama_kurir' => $_POST['nama_kurir'],
                    ':no_telp' => !empty($_POST['no_telp']) ? $_POST['no_telp'] : null,
                    ':area_operasi' => !empty($_POST['area_operasi']) ? $_POST['area_operasi'] : null,
                    ':kendaraan' => $_POST['kendaraan'],
                    ':status_kurir' => $_POST['status_kurir']
                ]);
                $msg = '<div class="alert alert-success">✅ Kurir baru berhasil ditambahkan!</div>';

            } elseif ($_POST['action'] === 'update') {
                // Logika Update data kurir berdasarkan id_kurir
                $stmt = $pdo->prepare("UPDATE kurir SET 
                                       nama_kurir = :nama_kurir, 
                                       no_telp = :no_telp, 
                                       area_operasi = :area_operasi, 
                                       kendaraan = :kendaraan, 
                                       status_kurir = :status_kurir 
                                       WHERE id_kurir = :id_kurir");
                
                $stmt->execute([
                    ':id_kurir' => $_POST['id_kurir'],
                    ':nama_kurir' => $_POST['nama_kurir'],
                    ':no_telp' => !empty($_POST['no_telp']) ? $_POST['no_telp'] : null,
                    ':area_operasi' => !empty($_POST['area_operasi']) ? $_POST['area_operasi'] : null,
                    ':kendaraan' => $_POST['kendaraan'],
                    ':status_kurir' => $_POST['status_kurir']
                ]);
                $msg = '<div class="alert alert-success">✅ Data kurir berhasil diperbarui!</div>';

            } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
                // Logika Hapus data kurir
                $stmt = $pdo->prepare("DELETE FROM kurir WHERE id_kurir = :id_kurir");
                $stmt->execute([':id_kurir' => $_POST['id']]);
                $msg = '<div class="alert alert-success">✅ Data kurir berhasil dihapus!</div>';
            }
        } catch (PDOException $e) {
            $msg = '<div class="alert alert-danger">❌ Gagal memproses data: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}

// Ambil data kurir dari database XAMPP
$kurir_list = $pdo ? $pdo->query("SELECT * FROM kurir ORDER BY nama_kurir")->fetchAll() : [
    ['id_kurir'=>'KUR-001','nama_kurir'=>'Budi Kurniawan','no_telp'=>'08111000001','area_operasi'=>'Jakarta Pusat','status_kurir'=>'aktif','kendaraan'=>'Motor','rating'=>4.8],
    ['id_kurir'=>'KUR-002','nama_kurir'=>'Dian Permana','no_telp'=>'08111000002','area_operasi'=>'Bandung Kota','status_kurir'=>'aktif','kendaraan'=>'Motor','rating'=>4.6],
    ['id_kurir'=>'KUR-003','nama_kurir'=>'Eko Prasetyo','no_telp'=>'08111000003','area_operasi'=>'Surabaya Barat','status_kurir'=>'cuti','kendaraan'=>'Mobil Box','rating'=>4.3],
    ['id_kurir'=>'KUR-004','nama_kurir'=>'Fajar Hidayat','no_telp'=>'08111000004','area_operasi'=>'Medan Kota','status_kurir'=>'aktif','kendaraan'=>'Motor','rating'=>4.9],
    ['id_kurir'=>'KUR-005','nama_kurir'=>'Gilang Ramadhan','no_telp'=>'08111000005','area_operasi'=>'Yogyakarta','status_kurir'=>'nonaktif','kendaraan'=>'Sepeda','rating'=>3.8],
    ['id_kurir'=>'KUR-006','nama_kurir'=>'Hani Susanti','no_telp'=>'08111000006','area_operasi'=>'Semarang','status_kurir'=>'aktif','kendaraan'=>'Motor','rating'=>4.7],
];

$kendaraan_icons = ['Motor'=>'🏍️','Mobil Box'=>'🚐','Sepeda'=>'🚲','Truk'=>'🚛'];
?>

<?= $msg ?>

<div class="page-header">
  <div class="page-header-left">
    <div class="page-title">🚚 Data Kurir</div>
    <div class="page-subtitle"><?= count($kurir_list) ?> kurir terdaftar</div>
  </div>
  <button type="button" class="btn btn-primary" onclick="openTambahKurirModal()">➕ Tambah Kurir</button>
</div>

<div style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap">
  <?php
  $aktif = count(array_filter($kurir_list, fn($k)=>$k['status_kurir']==='aktif'));
  $cuti  = count(array_filter($kurir_list, fn($k)=>$k['status_kurir']==='cuti'));
  $nona  = count(array_filter($kurir_list, fn($k)=>$k['status_kurir']==='nonaktif'));
  $items = [['🟢','Kurir Aktif',$aktif,'green'],['🟡','Cuti',$cuti,'amber'],['🔴','Nonaktif',$nona,'red']];
  foreach ($items as [$ic,$lbl,$val,$cls]):
  ?>
  <div style="background:var(--white);border:1px solid var(--blue-100);border-radius:var(--radius-md);padding:14px 20px;display:flex;align-items:center;gap:12px;box-shadow:var(--shadow-sm)">
    <span style="font-size:20px"><?= $ic ?></span>
    <div>
      <div style="font-size:20px;font-weight:700;color:var(--blue-900);font-family:var(--font-main)"><?= $val ?></div>
      <div style="font-size:12px;color:var(--gray-400)"><?= $lbl ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="filter-bar">
  <div class="filter-search">
    <span>🔍</span>
    <input type="text" id="searchKurir" placeholder="Cari nama, area..." oninput="filterKurir()">
  </div>
  <select class="filter-select" id="filterStatus" onchange="filterKurir()">
    <option value="">Semua Status</option>
    <option>aktif</option><option>cuti</option><option>nonaktif</option>
  </select>
  <select class="filter-select" id="filterKendaraan" onchange="filterKurir()">
    <option value="">Semua Kendaraan</option>
    <option>Motor</option><option>Mobil Box</option><option>Sepeda</option><option>Truk</option>
  </select>
</div>

<div class="card">
  <div class="table-wrapper">
    <table id="kurirTable">
      <thead>
        <tr>
          <th>#</th>
          <th>ID Kurir</th>
          <th>Nama Kurir</th>
          <th>No. Telepon</th>
          <th>Area Operasi</th>
          <th>Kendaraan</th>
          <th>Rating</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($kurir_list as $i => $k): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><strong><?= htmlspecialchars($k['id_kurir']) ?></strong></td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:34px;height:34px;background:var(--blue-100);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--blue-700);flex-shrink:0">
                <?= strtoupper(substr($k['nama_kurir'],0,1)) ?>
              </div>
              <span style="font-weight:600;color:var(--blue-900)"><?= htmlspecialchars($k['nama_kurir']) ?></span>
            </div>
          </td>
          <td><?= htmlspecialchars($k['no_telp']) ?></td>
          <td>📍 <?= htmlspecialchars($k['area_operasi']) ?></td>
          <td><?= ($kendaraan_icons[$k['kendaraan']] ?? '🚗') . ' ' . htmlspecialchars($k['kendaraan']) ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:4px">
              <span style="color:#f59e0b">★</span>
              <strong><?= number_format($k['rating'],1) ?></strong>
            </div>
          </td>
          <td><?= badge_status($k['status_kurir']) ?></td>
          <td>
            <div style="display:flex;gap:6px">
              <button type="button" class="btn btn-secondary btn-sm" onclick="bukaEditKurir(<?= htmlspecialchars(json_encode($k)) ?>)">✏️</button>
              
              <form method="POST" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kurir <?= htmlspecialchars($k['nama_kurir'], ENT_QUOTES) ?>?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $k['id_kurir'] ?>">
                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal-overlay" id="modalKurir" style="display:none">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="modalTitleKurir">🚚 Tambah Kurir Baru</div>
      <button class="modal-close" onclick="closeModal('modalKurir')">✕</button>
    </div>
    <form method="POST" id="formKurir">
      <input type="hidden" name="action" id="formActionKurir" value="add">
      <div class="modal-body">
        <div class="form-grid form-grid-2">
          <div class="form-group">
            <label class="form-label">ID Kurir <span>*</span></label>
            <input type="text" name="id_kurir" id="kr_id" class="form-control" placeholder="KUR-001" required>
          </div>
          <div class="form-group">
            <label class="form-label">Nama Kurir <span>*</span></label>
            <input type="text" name="nama_kurir" id="kr_nama" class="form-control" placeholder="Nama lengkap" required>
          </div>
          <div class="form-group">
            <label class="form-label">No. Telepon</label>
            <input type="text" name="no_telp" id="kr_telp" class="form-control" placeholder="08xxxxxxxxxx">
          </div>
          <div class="form-group">
            <label class="form-label">Area Operasi</label>
            <input type="text" name="area_operasi" id="kr_area" class="form-control" placeholder="Kota area kerja">
          </div>
          <div class="form-group">
            <label class="form-label">Kendaraan</label>
            <select name="kendaraan" id="kr_kendaraan" class="form-control">
              <option>Motor</option><option>Mobil Box</option><option>Sepeda</option><option>Truk</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status_kurir" id="kr_status" class="form-control">
              <option value="aktif">Aktif</option>
              <option value="nonaktif">Nonaktif</option>
              <option value="cuti">Cuti</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalKurir')">Batal</button>
        <button type="submit" class="btn btn-primary">💾 Simpan Kurir</button>
      </div>
    </form>
  </div>
</div>

<script>
function filterKurir() {
  const q = document.getElementById('searchKurir').value.toLowerCase();
  const s = document.getElementById('filterStatus').value.toLowerCase();
  const kend = document.getElementById('filterKendaraan').value.toLowerCase();
  
  document.querySelectorAll('#kurirTable tbody tr').forEach(tr => {
    const text = tr.innerText.toLowerCase();
    const statusKolom = tr.querySelector('td:nth-child(8)').innerText.toLowerCase().trim();
    const kendaraanKolom = tr.querySelector('td:nth-child(6)').innerText.toLowerCase().trim();
    const matchQuery = text.includes(q);
    const matchStatus = (!s || statusKolom === s);
    const matchKendaraan = (!kend || kendaraanKolom.includes(kend));
    tr.style.display = (matchQuery && matchStatus && matchKendaraan) ? '' : 'none';
  });
}

// Fungsi JavaScript untuk Mengatur ulang Modal menjadi Mode Tambah Baru
function openTambahKurirModal() {
  document.getElementById('modalTitleKurir').innerText = "🚚 Tambah Kurir Baru";
  document.getElementById('formActionKurir').value = "add";
  document.getElementById('kr_id').readOnly = false;
  document.getElementById('formKurir').reset();
  openModal('modalKurir');
}

// Fungsi JavaScript Utama untuk Mengisi data lama kurir ke form modal input saat mode Edit
function bukaEditKurir(data) {
  document.getElementById('modalTitleKurir').innerText = "✏️ Edit Data Kurir";
  document.getElementById('formActionKurir').value = "update";
  
  // Memasukkan data objek ke masing-masing input modal berdasarkan ID komponennya
  document.getElementById('kr_id').value = data.id_kurir;
  document.getElementById('kr_id').readOnly = true; 
  document.getElementById('kr_nama').value = data.nama_kurir;
  document.getElementById('kr_telp').value = data.no_telp;
  document.getElementById('kr_area').value = data.area_operasi;
  document.getElementById('kr_kendaraan').value = data.kendaraan;
  document.getElementById('kr_status').value = data.status_kurir;
  
  // Tampilkan modal popup overlay
  openModal('modalKurir');
}
</script>
