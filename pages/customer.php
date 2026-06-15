<?php
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO customer (id_customer, nama_customer, alamat_asal, kota_asal, provinsi_asal, no_telp, email, tgl_daftar) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $_POST['id_customer'], $_POST['nama_customer'], $_POST['alamat_asal'],
                $_POST['kota_asal'], $_POST['provinsi_asal'], $_POST['no_telp'], $_POST['email']
            ]);
            $msg = '<div class="alert alert-success">✅ Customer berhasil ditambahkan!</div>';
        } catch (PDOException $e) {
            $msg = '<div class="alert alert-danger">❌ Gagal menambah customer: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } elseif ($_POST['action'] === 'update') {
        // Logika PHP untuk menyimpan perubahan data hasil edit
        try {
            $stmt = $pdo->prepare("UPDATE customer SET nama_customer=?, alamat_asal=?, kota_asal=?, provinsi_asal=?, no_telp=?, email=? WHERE id_customer=?");
            $stmt->execute([
                $_POST['nama_customer'], $_POST['alamat_asal'], $_POST['kota_asal'],
                $_POST['provinsi_asal'], $_POST['no_telp'], $_POST['email'], $_POST['id_customer']
            ]);
            $msg = '<div class="alert alert-success">✅ Data customer berhasil diperbarui!</div>';
        } catch (PDOException $e) {
            $msg = '<div class="alert alert-danger">❌ Gagal memperbarui customer: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
        try {
            $stmt = $pdo->prepare("DELETE FROM customer WHERE id_customer=?");
            $stmt->execute([$_POST['id']]);
            $msg = '<div class="alert alert-success">✅ Customer berhasil dihapus.</div>';
        } catch (PDOException $e) {
            $msg = '<div class="alert alert-danger">❌ Gagal menghapus customer: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}

// Ambil data dari database XAMPP
$customers = $pdo ? $pdo->query("SELECT * FROM customer ORDER BY tgl_daftar DESC")->fetchAll() : [
    ['id_customer'=>'CST-001','nama_customer'=>'Budi Santoso','kota_asal'=>'Jakarta','provinsi_asal'=>'DKI Jakarta','no_telp'=>'08123456789','email'=>'budi@email.com','tgl_daftar'=>'2024-01-15 10:00:00','alamat_asal'=>'Jl. Sudirman No.1'],
    ['id_customer'=>'CST-002','nama_customer'=>'Siti Rahayu','kota_asal'=>'Bandung','provinsi_asal'=>'Jawa Barat','no_telp'=>'08198765432','email'=>'siti@email.com','tgl_daftar'=>'2024-02-20 09:30:00','alamat_asal'=>'Jl. Dago No.45'],
    ['id_customer'=>'CST-003','nama_customer'=>'Ahmad Fauzi','kota_asal'=>'Surabaya','provinsi_asal'=>'Jawa Timur','no_telp'=>'08112345678','email'=>'ahmad@email.com','tgl_daftar'=>'2024-03-05 14:00:00','alamat_asal'=>'Jl. Pemuda No.12'],
    ['id_customer'=>'CST-004','nama_customer'=>'Dewi Lestari','kota_asal'=>'Yogyakarta','provinsi_asal'=>'DIY','no_telp'=>'08156781234','email'=>'dewi@email.com','tgl_daftar'=>'2024-03-18 11:00:00','alamat_asal'=>'Jl. Malioboro No.88'],
    ['id_customer'=>'CST-005','nama_customer'=>'Hendra Wijaya','kota_asal'=>'Medan','provinsi_asal'=>'Sumatera Utara','no_telp'=>'08167891234','email'=>'hendra@email.com','tgl_daftar'=>'2024-04-02 08:00:00','alamat_asal'=>'Jl. Gatot Subroto No.5'],
];
?>

<?= $msg ?>

<div class="page-header">
  <div class="page-header-left">
    <div class="page-title">👥 Data Customer</div>
    <div class="page-subtitle">Total <?= count($customers) ?> customer terdaftar</div>
  </div>
  <button type="button" class="btn btn-primary" onclick="openTambahModal()">
    ➕ Tambah Customer
  </button>
</div>

<div class="filter-bar">
  <div class="filter-search">
    <span>🔍</span>
    <input type="text" id="searchInput" placeholder="Cari nama, kota, email..." oninput="filterTable()">
  </div>
  <select class="filter-select" id="filterProvinsi" onchange="filterTable()">
    <option value="">Semua Provinsi</option>
    <?php
    $provinsi = array_unique(array_column($customers, 'provinsi_asal'));
    foreach ($provinsi as $p) echo "<option value='$p'>$p</option>";
    ?>
  </select>
</div>

<div class="card">
  <div class="table-wrapper">
    <table id="dataTable">
      <thead>
        <tr>
          <th>#</th>
          <th>ID Customer</th>
          <th>Nama Customer</th>
          <th>Kota Asal</th>
          <th>Provinsi</th>
          <th>No. Telepon</th>
          <th>Email</th>
          <th>Tgl Daftar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($customers as $i => $row): ?>
        <tr data-provinsi="<?= $row['provinsi_asal'] ?>">
          <td><?= $i+1 ?></td>
          <td><strong><?= htmlspecialchars($row['id_customer']) ?></strong></td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:32px;height:32px;background:var(--blue-100);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--blue-700);font-size:13px;flex-shrink:0">
                <?= strtoupper(substr($row['nama_customer'],0,1)) ?>
              </div>
              <div>
                <div style="font-weight:600;color:var(--blue-900)"><?= htmlspecialchars($row['nama_customer']) ?></div>
                <div style="font-size:11.5px;color:var(--gray-400)"><?= htmlspecialchars($row['alamat_asal']) ?></div>
              </div>
            </div>
          </td>
          <td><?= htmlspecialchars($row['kota_asal']) ?></td>
          <td><span class="badge badge-blue"><?= htmlspecialchars($row['provinsi_asal']) ?></span></td>
          <td><?= htmlspecialchars($row['no_telp']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= tgl_indo($row['tgl_daftar']) ?></td>
          <td>
            <div style="display:flex;gap:6px">
              <button type="button" class="btn btn-secondary btn-sm" onclick="bukaEditCustomer(<?= htmlspecialchars(json_encode($row)) ?>)">✏️</button>
              
              <form method="POST" style="display:inline" onsubmit="return confirm('Hapus customer ini?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $row['id_customer'] ?>">
                <button class="btn btn-danger btn-sm" type="submit">🗑️</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if (empty($customers)): ?>
  <div class="empty-state">
    <div class="empty-state-icon">👥</div>
    <div class="empty-state-title">Belum ada customer</div>
    <div class="empty-state-desc">Tambah customer pertama Anda</div>
  </div>
  <?php endif; ?>
</div>

<div class="modal-overlay" id="modalTambah" style="display:none">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="modalTitle">➕ Tambah Customer Baru</div>
      <button class="modal-close" onclick="closeModal('modalTambah')">✕</button>
    </div>
    <form method="POST" id="formCustomer">
      <input type="hidden" name="action" id="formAction" value="add">
      <div class="modal-body">
        <div class="form-grid form-grid-2">
          <div class="form-group">
            <label class="form-label">ID Customer <span>*</span></label>
            <input type="text" name="id_customer" id="cst_id" class="form-control" placeholder="CST-001" required>
          </div>
          <div class="form-group">
            <label class="form-label">Nama Customer <span>*</span></label>
            <input type="text" name="nama_customer" id="cst_nama" class="form-control" placeholder="Nama lengkap" required>
          </div>
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Alamat Asal</label>
            <textarea name="alamat_asal" id="cst_alamat" class="form-control" placeholder="Alamat lengkap" rows="2"></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Kota Asal</label>
            <input type="text" name="kota_asal" id="cst_kota" class="form-control" placeholder="Jakarta">
          </div>
          <div class="form-group">
            <label class="form-label">Provinsi</label>
            <input type="text" name="provinsi_asal" id="cst_provinsi" class="form-control" placeholder="DKI Jakarta">
          </div>
          <div class="form-group">
            <label class="form-label">No. Telepon</label>
            <input type="text" name="no_telp" id="cst_telp" class="form-control" placeholder="08xxxxxxxxxx">
          </div>
          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" id="cst_email" class="form-control" placeholder="email@domain.com">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalTambah')">Batal</button>
        <button type="submit" class="btn btn-primary">💾 Simpan Customer</button>
      </div>
    </form>
  </div>
</div>

<script>
function filterTable() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  const prov = document.getElementById('filterProvinsi').value;
  document.querySelectorAll('#dataTable tbody tr').forEach(tr => {
    const text = tr.innerText.toLowerCase();
    const trProv = tr.dataset.provinsi;
    tr.style.display = (text.includes(q) && (!prov || trProv === prov)) ? '' : 'none';
  });
}

// Fungsi JavaScript untuk Reset Modal ke Mode Tambah Baru
function openTambahModal() {
  document.getElementById('modalTitle').innerText = "➕ Tambah Customer Baru";
  document.getElementById('formAction').value = "add";
  document.getElementById('cst_id').readOnly = false;
  document.getElementById('formCustomer').reset();
  openModal('modalTambah');
}

function bukaEditCustomer(data) {
  document.getElementById('modalTitle').innerText = "✏️ Edit Data Customer";
  document.getElementById('formAction').value = "update";

  document.getElementById('cst_id').value = data.id_customer;
  document.getElementById('cst_id').readOnly = true; 
  document.getElementById('cst_nama').value = data.nama_customer;
  document.getElementById('cst_alamat').value = data.alamat_asal;
  document.getElementById('cst_kota').value = data.kota_asal;
  document.getElementById('cst_provinsi').value = data.provinsi_asal;
  document.getElementById('cst_telp').value = data.no_telp;
  document.getElementById('cst_email').value = data.email;

  openModal('modalTambah');
}
</script>
