<?php
// === LOGIKA PHP TRANS INDUK & DETAIL BARANG ===
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($pdo) {
        try {
            // 1. TAMBAH PENGIRIMAN INDUK
            if ($_POST['action'] === 'add') {
                $stmt = $pdo->prepare("INSERT INTO pengiriman (id_pengiriman, id_customer, tgl_pengiriman, total_biaya, penerima_nama, penerima_telp, alamat_tujuan, kota_tujuan, provinsi_tujuan, catatan) 
                                       VALUES (:id_pengiriman, :id_customer, :tgl_pengiriman, :total_biaya, :penerima_nama, :penerima_telp, :alamat_tujuan, :kota_tujuan, :provinsi_tujuan, :catatan)");
                
                $tgl = !empty($_POST['tgl_pengiriman']) ? $_POST['tgl_pengiriman'] : date('Y-m-d');
                $stmt->execute([
                    ':id_pengiriman'    => $_POST['id_pengiriman'],
                    ':id_customer'      => !empty($_POST['id_customer']) ? $_POST['id_customer'] : null,
                    ':tgl_pengiriman'   => $tgl,
                    ':total_biaya'      => !empty($_POST['total_biaya']) ? $_POST['total_biaya'] : 0,
                    ':penerima_nama'    => !empty($_POST['penerima_nama']) ? $_POST['penerima_nama'] : null,
                    ':penerima_telp'    => !empty($_POST['penerima_telp']) ? $_POST['penerima_telp'] : null,
                    ':alamat_tujuan'    => !empty($_POST['alamat_tujuan']) ? $_POST['alamat_tujuan'] : null,
                    ':kota_tujuan'      => !empty($_POST['kota_tujuan']) ? $_POST['kota_tujuan'] : null,
                    ':provinsi_tujuan'  => !empty($_POST['provinsi_tujuan']) ? $_POST['provinsi_tujuan'] : null,
                    ':catatan'          => !empty($_POST['catatan']) ? $_POST['catatan'] : null
                ]);
                header("Location: index.php?page=pengiriman");
                exit;

            // 2. UPDATE PENGIRIMAN INDUK
            } elseif ($_POST['action'] === 'update') {
                $stmt = $pdo->prepare("UPDATE pengiriman SET 
                                       id_customer = :id_customer, 
                                       tgl_pengiriman = :tgl_pengiriman, 
                                       total_biaya = :total_biaya, 
                                       penerima_nama = :penerima_nama, 
                                       penerima_telp = :penerima_telp, 
                                       alamat_tujuan = :alamat_tujuan, 
                                       kota_tujuan = :kota_tujuan, 
                                       provinsi_tujuan = :provinsi_tujuan, 
                                       catatan = :catatan
                                       WHERE id_pengiriman = :id_pengiriman");
                
                $stmt->execute([
                    ':id_pengiriman'    => $_POST['id_pengiriman'],
                    ':id_customer'      => !empty($_POST['id_customer']) ? $_POST['id_customer'] : null,
                    ':tgl_pengiriman'   => $_POST['tgl_pengiriman'],
                    ':total_biaya'      => !empty($_POST['total_biaya']) ? $_POST['total_biaya'] : 0,
                    ':penerima_nama'    => !empty($_POST['penerima_nama']) ? $_POST['penerima_nama'] : null,
                    ':penerima_telp'    => !empty($_POST['penerima_telp']) ? $_POST['penerima_telp'] : null,
                    ':alamat_tujuan'    => !empty($_POST['alamat_tujuan']) ? $_POST['alamat_tujuan'] : null,
                    ':kota_tujuan'      => !empty($_POST['kota_tujuan']) ? $_POST['kota_tujuan'] : null,
                    ':provinsi_tujuan'  => !empty($_POST['provinsi_tujuan']) ? $_POST['provinsi_tujuan'] : null,
                    ':catatan'          => !empty($_POST['catatan']) ? $_POST['catatan'] : null
                ]);
                header("Location: index.php?page=pengiriman");
                exit;

            // 3. HAPUS PENGIRIMAN INDUK
            } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
                $delDetail = $pdo->prepare("DELETE FROM detail_pengiriman WHERE id_pengiriman = ?");
                $delDetail->execute([$_POST['id']]);

                $stmt = $pdo->prepare("DELETE FROM pengiriman WHERE id_pengiriman = :id_pengiriman");
                $stmt->execute([':id_pengiriman' => $_POST['id']]);
                header("Location: index.php?page=pengiriman");
                exit;
            }
            
            // 4. TAMBAH BARANG KE DETAIL PENGIRIMAN
            elseif ($_POST['action'] === 'add_item') {
                $id_pengiriman = $_POST['id_pengiriman'];
                $id_barang = $_POST['id_barang'];
                $jumlah = !empty($_POST['jumlah_barang']) ? intval($_POST['jumlah_barang']) : 1;
                
                $stmtBarang = $pdo->prepare("SELECT berat_standar, harga_standar FROM barang WHERE id_barang = ?");
                $stmtBarang->execute([$id_barang]);
                $itemData = $stmtBarang->fetch();
                
                $berat_unit = $itemData ? $itemData['berat_standar'] : 0;
                $harga_unit = $itemData ? $itemData['harga_standar'] : 0;
                
                $berat_total = $berat_unit * $jumlah;
                $subtotal = $harga_unit * $jumlah;
                $catatan_barang = $_POST['catatan_barang'] ?? '';
                
                $stmtAdd = $pdo->prepare("INSERT INTO detail_pengiriman (id_pengiriman, id_barang, jumlah_barang, berat_barang, harga_unit, subtotal, catatan_barang) 
                                          VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmtAdd->execute([$id_pengiriman, $id_barang, $jumlah, $berat_total, $harga_unit, $subtotal, $catatan_barang]);
                
                $stmtUpdateInduk = $pdo->prepare("UPDATE pengiriman SET total_biaya = (SELECT SUM(subtotal) FROM detail_pengiriman WHERE id_pengiriman = ?) WHERE id_pengiriman = ?");
                $stmtUpdateInduk->execute([$id_pengiriman, $id_pengiriman]);

                header("Location: index.php?page=pengiriman&open_detail=" . urlencode($id_pengiriman));
                exit;
            } 
            
            // 5. HAPUS BARANG DARI DETAIL PENGIRIMAN
            elseif ($_POST['action'] === 'delete_item') {
                $id_pengiriman = $_POST['id_pengiriman'];
                $id_barang = $_POST['id_barang'];
                
                $stmtDelItem = $pdo->prepare("DELETE FROM detail_pengiriman WHERE id_pengiriman = ? AND id_barang = ?");
                $stmtDelItem->execute([$id_pengiriman, $id_barang]);
                
                $stmtUpdateInduk = $pdo->prepare("UPDATE pengiriman SET total_biaya = COALESCE((SELECT SUM(subtotal) FROM detail_pengiriman WHERE id_pengiriman = ?), 0) WHERE id_pengiriman = ?");
                $stmtUpdateInduk->execute([$id_pengiriman, $id_pengiriman]);
                
                header("Location: index.php?page=pengiriman&open_detail=" . urlencode($id_pengiriman));
                exit;
            }

        } catch (PDOException $e) {
            $msg = '<div class="alert alert-danger">❌ Gagal memproses data: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}

// Ambil data untuk list tabel utama
$pengiriman_list = [];
$master_barang = [];
$detail_barang_aktif = [];
$id_pgm_aktif = $_GET['open_detail'] ?? '';

if ($pdo) {
    try {
        // Query dibersihkan, tidak memanggil kolom status karena memang tidak ada di database
        $pengiriman_list = $pdo->query("SELECT p.*, c.nama_customer,
            COALESCE((SELECT SUM(jumlah_barang) FROM detail_pengiriman WHERE id_pengiriman = p.id_pengiriman), 0) AS total_barang,
            COALESCE((SELECT SUM(berat_barang) FROM detail_pengiriman WHERE id_pengiriman = p.id_pengiriman), 0) AS total_berat
            FROM pengiriman p 
            LEFT JOIN customer c ON p.id_customer = c.id_customer 
            ORDER BY p.id_pengiriman DESC")->fetchAll();
            
        $master_barang = $pdo->query("SELECT id_barang, nama_barang, harga_standar, berat_standar FROM barang ORDER BY nama_barang")->fetchAll();

        // Ambil isi rincian barang spesifik via PHP jika parameter open_detail ada di URL
        if (!empty($id_pgm_aktif)) {
            $stmtDetail = $pdo->prepare("SELECT d.*, b.nama_barang FROM detail_pengiriman d JOIN barang b ON d.id_barang = b.id_barang WHERE d.id_pengiriman = ?");
            $stmtDetail->execute([$id_pgm_aktif]);
            $detail_barang_aktif = $stmtDetail->fetchAll();
        }
    } catch (PDOException $e) {
        $msg = '<div class="alert alert-danger">❌ Database Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

<?= $msg ?>

<div class="page-header">
  <div class="page-header-left">
    <div class="page-title">🗺️ Data Pengiriman</div>
    <div class="page-subtitle"><?= count($pengiriman_list) ?> total transaksi pengiriman</div>
  </div>
  <div style="display:flex;gap:10px">
    <button class="btn btn-secondary" onclick="exportTableToExcel('pgmTable', 'Data-Manifes-Pengiriman')">📥 Export Excel</button>
    <button class="btn btn-primary" onclick="openTambahPengirimanModal()">➕ Buat Pengiriman</button>
  </div>
</div>

<div class="filter-bar">
  <div class="filter-search">
    <span>🔍</span>
    <input type="text" id="searchPgm" placeholder="Cari ID, customer, kota tujuan..." oninput="filterPgm()">
  </div>
  <input type="date" class="filter-select" id="filterTgl" onchange="filterPgm()" style="cursor:pointer">
</div>

<div class="card">
  <div class="table-wrapper">
    <table id="pgmTable">
      <thead>
        <tr>
          <th>ID Pengiriman</th>
          <th>Customer</th>
          <th>Tgl Pengiriman</th>
          <th>Penerima</th>
          <th>Kota Tujuan</th>
          <th>Total Barang</th>
          <th>Total Berat</th>
          <th>Total Biaya</th>
          <th class="no-export">Aksi</th> 
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pengiriman_list as $row): ?>
        <tr data-tanggal="<?= $row['tgl_pengiriman'] ?? '' ?>">
          <td><strong style="color:var(--blue-600)"><?= htmlspecialchars($row['id_pengiriman']) ?></strong></td>
          <td><?= htmlspecialchars($row['nama_customer'] ?? '-') ?></td>
          <td><?= isset($row['tgl_pengiriman']) ? tgl_indo($row['tgl_pengiriman']) : '-' ?></td>
          <td><?= htmlspecialchars($row['penerima_nama'] ?? '-') ?></td>
          <td>
            <div style="font-weight:500">📍 <?= htmlspecialchars($row['kota_tujuan'] ?? '-') ?></div>
            <div style="font-size:11.5px;color:var(--gray-400)"><?= htmlspecialchars($row['provinsi_tujuan'] ?? '-') ?></div>
          </td>
          <td style="text-align:center">
             <button type="button" class="badge badge-blue" style="border:none; cursor:pointer;" onclick="bukaModalDetailBarang('<?= htmlspecialchars($row['id_pengiriman']) ?>')">
                📦 <?= $row['total_barang'] ?> Item (Detail)
             </button>
          </td>
          <td><?= number_format(($row['total_berat'] ?? 0), 1) ?> kg</td>
          <td style="font-weight:700;color:var(--blue-700)"><?= rupiah($row['total_biaya'] ?? 0) ?></td>
          <td class="no-export">
            <div style="display:flex;gap:5px">
              <button class="btn btn-secondary btn-sm" title="Edit" onclick="bukaEditPengiriman(<?= htmlspecialchars(json_encode($row)) ?>)">✏️</button>
              <form method="POST" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi <?= $row['id_pengiriman'] ?>?')">
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

<div class="modal-overlay" id="modalDetailBarang" style="display:none">
  <div class="modal" style="max-width:750px;">
    <div class="modal-header">
      <div class="modal-title">📦 Rincian Item Barang (<span id="txtDetailIDPgm"><?= htmlspecialchars($id_pgm_aktif) ?></span>)</div>
      <button class="modal-close" onclick="closeModalDetail()">✕</button>
    </div>
    <div class="modal-body">
      
      <form method="POST" style="background: var(--blue-50); padding: 12px; border-radius: 8px; margin-bottom: 16px;">
        <input type="hidden" name="action" value="add_item">
        <input type="hidden" name="id_pengiriman" id="inputDetailIDPgm" value="<?= htmlspecialchars($id_pgm_aktif) ?>">
        
        <div style="display:grid; grid-template-columns: 2fr 1fr 2fr 1fr; gap:10px; align-items:end;">
          <div class="form-group" style="margin:0">
            <label class="form-label" style="font-size:11px;">Pilih Barang</label>
            <select name="id_barang" class="form-control" style="padding:6px" required>
              <option value="">-- Pilih --</option>
              <?php foreach($master_barang as $brg): ?>
                <option value="<?= $brg['id_barang'] ?>"><?= htmlspecialchars($brg['nama_barang']) ?> (<?= number_format($brg['berat_standar'],1) ?>kg)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group" style="margin:0">
            <label class="form-label" style="font-size:11px;">Qty</label>
            <input type="number" name="jumlah_barang" class="form-control" style="padding:6px" value="1" min="1" required>
          </div>
          <div class="form-group" style="margin:0">
            <label class="form-label" style="font-size:11px;">Catatan Unit</label>
            <input type="text" name="catatan_barang" class="form-control" style="padding:6px" placeholder="Fragile">
          </div>
          <button type="submit" class="btn btn-primary" style="padding: 8px; font-size: 13px;">➕ Masukkan</button>
        </div>
      </form>

      <table class="form-grid" style="width:100%; border-collapse:collapse; font-size:13px;">
        <thead>
          <tr style="background:var(--blue-900); color:#fff; text-align:left;">
            <th style="padding:8px">Nama Barang</th>
            <th style="padding:8px; text-align:center">Qty</th>
            <th style="padding:8px">Harga Unit</th>
            <th style="padding:8px">Total Berat</th>
            <th style="padding:8px">Subtotal</th>
            <th style="padding:8px">Keterangan</th>
            <th style="padding:8px; text-align:center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($detail_barang_aktif)): ?>
            <tr><td colspan="7" style="text-align:center; padding:15px; color:var(--gray-400)">Belum ada item barang di pengiriman ini. Silakan tambahkan diatas.</td></tr>
          <?php else: ?>
            <?php foreach ($detail_barang_aktif as $item): ?>
              <tr style="border-bottom: 1px solid var(--blue-50);">
                <td style="padding:8px"><strong><?= htmlspecialchars($item['nama_barang']) ?></strong></td>
                <td style="padding:8px; text-align:center"><?= $item['jumlah_barang'] ?></td>
                <td style="padding:8px">Rp <?= number_format($item['harga_unit'], 0, ',', '.') ?></td>
                <td style="padding:8px"><?= number_format($item['berat_barang'], 1) ?> kg</td>
                <td style="padding:8px; font-weight:600; color:var(--blue-700)">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                <td style="padding:8px; font-size:11.5px; color:var(--gray-400)"><?= htmlspecialchars($item['catatan_barang'] ?: '-') ?></td>
                <td style="padding:8px; text-align:center">
                   <form method="POST" style="display:inline" onsubmit="return confirm('Hapus item ini?')">
                      <input type="hidden" name="action" value="delete_item">
                      <input type="hidden" name="id_pengiriman" value="<?= htmlspecialchars($item['id_pengiriman']) ?>">
                      <input type="hidden" name="id_barang" value="<?= htmlspecialchars($item['id_barang']) ?>">
                      <button type="submit" style="background:none; border:none; cursor:pointer; color:red;" title="Hapus Barang">🗑️</button>
                   </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

<div class="modal-overlay" id="modalPengiriman" style="display:none">
  <div class="modal" style="max-width:640px">
    <div class="modal-header">
      <div class="modal-title" id="modalTitlePengiriman">🗺️ Buat Pengiriman Baru</div>
      <button class="modal-close" onclick="closeModal('modalPengiriman')">✕</button>
    </div>
    <form method="POST" id="formPengiriman">
      <input type="hidden" name="action" id="formActionPengiriman" value="add">
      <div class="modal-body">
        <div style="font-size:12px;font-weight:700;color:var(--blue-700);margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid var(--blue-50)">📋 Info Pengiriman</div>
        <div class="form-grid form-grid-2" style="margin-bottom:20px">
          <div class="form-group">
            <label class="form-label">ID Pengiriman <span>*</span></label>
            <input type="text" name="id_pengiriman" id="pgm_id" class="form-control" placeholder="PGM-0001" required>
          </div>
          <div class="form-group">
            <label class="form-label">Customer <span>*</span></label>
            <select name="id_customer" id="pgm_customer" class="form-control" required>
              <option value="">-- Pilih Customer --</option>
              <?php 
              if($pdo): 
                try {
                  $custs=$pdo->query("SELECT id_customer,nama_customer FROM customer ORDER BY nama_customer")->fetchAll(); 
                  foreach($custs as $c): 
                    echo '<option value="'.$c['id_customer'].'">'.htmlspecialchars($c['nama_customer']).'</option>';
                  endforeach;
                } catch(PDOException $e){}
              endif; 
              ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Tanggal Pengiriman</label>
            <input type="date" name="tgl_pengiriman" id="pgm_tgl" class="form-control">
          </div>
          <div class="form-group">
            <label class="form-label">Total Biaya (Rp) <small style="color:var(--gray-400)">(Kalkulasi Otomatis)</small></label>
            <input type="number" name="total_biaya" id="pgm_biaya" class="form-control" placeholder="0" readonly style="background:#f5f5f5">
          </div>
        </div>

        <div style="font-size:12px;font-weight:700;color:var(--blue-700);margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid var(--blue-50)">📍 Alamat Tujuan</div>
        <div class="form-grid form-grid-2">
          <div class="form-group">
            <label class="form-label">Nama Penerima</label>
            <input type="text" name="penerima_nama" id="pgm_penerima" class="form-control" placeholder="Nama penerima">
          </div>
          <div class="form-group">
            <label class="form-label">Telepon Penerima</label>
            <input type="text" name="penerima_telp" id="pgm_telp" class="form-control" placeholder="08xx">
          </div>
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Alamat Tujuan</label>
            <textarea name="alamat_tujuan" id="pgm_alamat" class="form-control" rows="2" placeholder="Alamat lengkap penerima"></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Kota Tujuan</label>
            <input type="text" name="kota_tujuan" id="pgm_kota" class="form-control" placeholder="Surabaya">
          </div>
          <div class="form-group">
            <label class="form-label">Provinsi Tujuan</label>
            <input type="text" name="provinsi_tujuan" id="pgm_provinsi" class="form-control" placeholder="Jawa Timur">
          </div>
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" id="pgm_catatan" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalPengiriman')">Batal</button>
        <button type="submit" class="btn btn-primary">💾 Simpan Pengiriman</button>
      </div>
    </form>
  </div>
</div>

<script>
function bukaModalDetailBarang(idPgm) {
  window.location.search = `?page=pengiriman&open_detail=${encodeURIComponent(idPgm)}`;
}

function closeModalDetail() {
  window.location.search = `?page=pengiriman`;
}

window.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('open_detail')) {
     openModal('modalDetailBarang');
  }
});

// Fungsi filter diperbarui, tidak melakukan pencocokan variabel status lagi
function filterPgm() {
  const q = document.getElementById('searchPgm').value.toLowerCase();
  const tgl = document.getElementById('filterTgl').value;
  
  document.querySelectorAll('#pgmTable tbody tr').forEach(tr => {
    const text = tr.innerText.toLowerCase();
    const trTgl = tr.dataset.tanggal;
    
    const matchQuery = text.includes(q);
    const matchTanggal = (!tgl || trTgl === tgl);
    
    tr.style.display = (matchQuery && matchTanggal) ? '' : 'none';
  });
}

function openTambahPengirimanModal() {
  document.getElementById('modalTitlePengiriman').innerText = "🗺️ Buat Pengiriman Baru";
  document.getElementById('formActionPengiriman').value = "add";
  document.getElementById('pgm_id').readOnly = false;
  document.getElementById('formPengiriman').reset();
  openModal('modalPengiriman');
}

function bukaEditPengiriman(data) {
  document.getElementById('modalTitlePengiriman').innerText = "✏️ Edit Transaksi Pengiriman";
  document.getElementById('formActionPengiriman').value = "update";
  
  document.getElementById('pgm_id').value = data.id_pengiriman;
  document.getElementById('pgm_id').readOnly = true;
  document.getElementById('pgm_customer').value = data.id_customer;
  document.getElementById('pgm_tgl').value = data.tgl_pengiriman;
  document.getElementById('pgm_biaya').value = data.total_biaya;
  
  document.getElementById('pgm_penerima').value = data.penerima_nama;
  document.getElementById('pgm_telp').value = data.penerima_telp;
  document.getElementById('pgm_alamat').value = data.alamat_tujuan;
  document.getElementById('pgm_kota').value = data.kota_tujuan;
  document.getElementById('pgm_provinsi').value = data.provinsi_tujuan;
  document.getElementById('pgm_catatan').value = data.catatan;
  
  openModal('modalPengiriman');
}

function exportTableToExcel(tableID, filename = '') {
    let tableSelect = document.getElementById(tableID);
    let tableClone = tableSelect.cloneNode(true);
    tableClone.querySelectorAll('.no-export').forEach(el => el.remove());
    let tableHTML = tableClone.outerHTML.replace(/ /g, '%20');
    filename = filename ? filename + '.xls' : 'excel_data.xls';
    let downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    
    if (navigator.msSaveOrOpenBlob) {
        let blob = new Blob(['\ufeff' + tableHTML], { type: 'application/vnd.ms-excel' });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = 'data:application/vnd.ms-excel,' + '\ufeff' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
    document.body.removeChild(downloadLink);
}
</script>