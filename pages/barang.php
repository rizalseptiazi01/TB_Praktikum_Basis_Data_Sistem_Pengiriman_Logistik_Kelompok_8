<?php

// TAMBAH, UPDATE, & HAPUS BARANG
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($pdo) {
        try {
            if ($_POST['action'] === 'add') {
                $stmt = $pdo->prepare("INSERT INTO barang (id_barang, nama_barang, kategori, berat_standar, harga_standar, deskripsi) 
                                       VALUES (:id_barang, :nama_barang, :kategori, :berat_standar, :harga_standar, :deskripsi)");
                
                // Eksekusi dengan mengikat data dari form
                $stmt->execute([
                    ':id_barang' => $_POST['id_barang'],
                    ':nama_barang' => $_POST['nama_barang'],
                    ':kategori' => $_POST['kategori'] ?? null,
                    ':berat_standar' => !empty($_POST['berat_standar']) ? $_POST['berat_standar'] : 0,
                    ':harga_standar' => !empty($_POST['harga_standar']) ? $_POST['harga_standar'] : 0,
                    ':deskripsi' => $_POST['deskripsi'] ?? null
                ]);
                echo "<script>window.location.href='index.php?page=barang';</script>";
                exit;

            } elseif ($_POST['action'] === 'update') {
                $stmt = $pdo->prepare("UPDATE barang SET 
                                       nama_barang = :nama_barang, 
                                       kategori = :kategori, 
                                       berat_standar = :berat_standar, 
                                       harga_standar = :harga_standar, 
                                       deskripsi = :deskripsi 
                                       WHERE id_barang = :id_barang");
                
                $stmt->execute([
                    ':id_barang' => $_POST['id_barang'],
                    ':nama_barang' => $_POST['nama_barang'],
                    ':kategori' => $_POST['kategori'] ?? null,
                    ':berat_standar' => !empty($_POST['berat_standar']) ? $_POST['berat_standar'] : 0,
                    ':harga_standar' => !empty($_POST['harga_standar']) ? $_POST['harga_standar'] : 0,
                    ':deskripsi' => $_POST['deskripsi'] ?? null
                ]);
                echo "<script>window.location.href='index.php?page=barang';</script>";
                exit;

            } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
                $stmt = $pdo->prepare("DELETE FROM barang WHERE id_barang = :id_barang");
                $stmt->execute([':id_barang' => $_POST['id']]);
                echo "<script>window.location.href='index.php?page=barang';</script>";
                exit;
            }
        } catch (PDOException $e) {
            echo "<script>alert('Gagal memproses data barang: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}

$barang_list = $pdo ? $pdo->query("SELECT * FROM barang ORDER BY nama_barang")->fetchAll() : [
    ['id_barang'=>'BRG-001','nama_barang'=>'Dokumen Penting','kategori'=>'Dokumen','deskripsi'=>'Surat resmi dan dokumen bisnis','berat_standar'=>0.5,'harga_standar'=>15000],
    ['id_barang'=>'BRG-002','nama_barang'=>'Elektronik Kecil','kategori'=>'Elektronik','deskripsi'=>'Handphone, tablet, dll','berat_standar'=>1.5,'harga_standar'=>35000],
    ['id_barang'=>'BRG-003','nama_barang'=>'Paket Makanan','kategori'=>'Makanan','deskripsi'=>'Makanan segar dan olahan','berat_standar'=>3.0,'harga_standar'=>25000],
    ['id_barang'=>'BRG-004','nama_barang'=>'Pakaian','kategori'=>'Fashion','deskripsi'=>'Baju, celana, aksesoris','berat_standar'=>2.0,'harga_standar'=>20000],
    ['id_barang'=>'BRG-005','nama_barang'=>'Peralatan Rumah','kategori'=>'Rumah Tangga','deskripsi'=>'Alat dapur, dekorasi','berat_standar'=>5.0,'harga_standar'=>45000],
    ['id_barang'=>'BRG-006','nama_barang'=>'Obat-obatan','kategori'=>'Kesehatan','deskripsi'=>'Obat dan suplemen','berat_standar'=>1.0,'harga_standar'=>30000],
];

$kategori_icons = ['Dokumen'=>'📄','Elektronik'=>'📱','Makanan'=>'🍱','Fashion'=>'👗','Rumah Tangga'=>'🏠','Kesehatan'=>'💊'];
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="page-title">📦 Data Barang</div>
    <div class="page-subtitle">Katalog <?= count($barang_list) ?> jenis barang</div>
  </div>
  <button type="button" class="btn btn-primary" onclick="openTambahBarangModal()">➕ Tambah Barang</button>
</div>

<div class="filter-bar">
  <div class="filter-search">
    <span>🔍</span>
    <input type="text" id="searchBarang" placeholder="Cari nama barang atau kategori..." oninput="filterBarang()">
  </div>
  <select class="filter-select" id="filterKat" onchange="filterBarang()">
    <option value="">Semua Kategori</option>
    <?php foreach (array_unique(array_column($barang_list,'kategori')) as $k): ?>
    <option><?= $k ?></option>
    <?php endforeach; ?>
  </select>
</div>

<div id="barangGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;margin-bottom:20px">
  <?php foreach ($barang_list as $b): ?>
  <div class="card" style="transition:var(--transition)" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
    <div class="card-body">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px">
        <div style="width:48px;height:48px;background:var(--blue-50);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px">
          <?= $kategori_icons[$b['kategori']] ?? '📦' ?>
        </div>
        <span class="badge badge-blue"><?= htmlspecialchars($b['kategori']) ?></span>
      </div>
      <div style="font-family:var(--font-main);font-size:15px;font-weight:700;color:var(--blue-900);margin-bottom:4px">
        <?= htmlspecialchars($b['nama_barang']) ?>
      </div>
      <div style="font-size:12px;color:var(--gray-400);margin-bottom:14px">
        <?= htmlspecialchars($b['deskripsi']) ?>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;padding-top:14px;border-top:1px solid var(--blue-50)">
        <div>
          <div style="font-size:10.5px;color:var(--gray-400);margin-bottom:2px">Berat Standar</div>
          <div style="font-weight:600;color:var(--blue-900)"><?= $b['berat_standar'] ?> kg</div>
        </div>
        <div>
          <div style="font-size:10.5px;color:var(--gray-400);margin-bottom:2px">Harga Standar</div>
          <div style="font-weight:600;color:var(--blue-600)"><?= rupiah($b['harga_standar']) ?></div>
        </div>
      </div>
      <div style="display:flex;gap:8px;margin-top:14px">
        <button type="button" class="btn btn-secondary btn-sm" style="flex:1" onclick="bukaEditBarang(<?= htmlspecialchars(json_encode($b)) ?>)">✏️ Edit</button>
        
        <form method="POST" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang <?= htmlspecialchars($b['nama_barang'], ENT_QUOTES) ?>?')">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= $b['id_barang'] ?>">
          <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="modal-overlay" id="modalBarang" style="display:none">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="modalTitleBarang">📦 Tambah Barang Baru</div>
      <button class="modal-close" onclick="closeModal('modalBarang')">✕</button>
    </div>
    <form method="POST" id="formBarang">
      <input type="hidden" name="action" id="formActionBarang" value="add">
      <div class="modal-body">
        <div class="form-grid form-grid-2">
          <div class="form-group">
            <label class="form-label">ID Barang <span>*</span></label>
            <input type="text" name="id_barang" id="brg_id" class="form-control" placeholder="BRG-001" required>
          </div>
          <div class="form-group">
            <label class="form-label">Nama Barang <span>*</span></label>
            <input type="text" name="nama_barang" id="brg_nama" class="form-control" placeholder="Nama barang" required>
          </div>
          <div class="form-group">
            <label class="form-label">Kategori</label>
            <select name="kategori" id="brg_kategori" class="form-control">
              <option>Dokumen</option><option>Elektronik</option><option>Makanan</option>
              <option>Fashion</option><option>Rumah Tangga</option><option>Kesehatan</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Berat Standar (kg)</label>
            <input type="number" step="0.01" name="berat_standar" id="brg_berat" class="form-control" placeholder="0.00">
          </div>
          <div class="form-group">
            <label class="form-label">Harga Standar (Rp)</label>
            <input type="number" name="harga_standar" id="brg_harga" class="form-control" placeholder="0">
          </div>
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="brg_deskripsi" class="form-control" placeholder="Deskripsi barang..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalBarang')">Batal</button>
        <button type="submit" class="btn btn-primary">💾 Simpan Barang</button>
      </div>
    </form>
  </div>
</div>

<script>
function filterBarang() {
  const q = document.getElementById('searchBarang').value.toLowerCase();
  const k = document.getElementById('filterKat').value;
  document.querySelectorAll('#barangGrid .card').forEach((card, i) => {
    const text = card.innerText.toLowerCase();
    const badges = card.querySelectorAll('.badge');
    let kat = '';
    badges.forEach(b => { if(!b.classList.contains('badge-blue') || b.innerText !== '') kat = b.innerText; });
    card.style.display = (text.includes(q) && (!k || text.includes(k.toLowerCase()))) ? '' : 'none';
  });
}

function openTambahBarangModal() {
  document.getElementById('modalTitleBarang').innerText = "📦 Tambah Barang Baru";
  document.getElementById('formActionBarang').value = "add";
  document.getElementById('brg_id').readOnly = false;
  document.getElementById('formBarang').reset();
  openModal('modalBarang');
}

function bukaEditBarang(data) {
  document.getElementById('modalTitleBarang').innerText = "✏️ Edit Data Barang";
  document.getElementById('formActionBarang').value = "update";
  
  document.getElementById('brg_id').value = data.id_barang;
  document.getElementById('brg_id').readOnly = true; 
  document.getElementById('brg_nama').value = data.nama_barang;
  document.getElementById('brg_kategori').value = data.kategori;
  document.getElementById('brg_berat').value = data.berat_standar;
  document.getElementById('brg_harga').value = data.harga_standar;
  document.getElementById('brg_deskripsi').value = data.deskripsi;
  
  openModal('modalBarang');
}
</script>
