<?php
require_once 'includes/config.php';

$allowed_pages = ['dashboard','customer','barang','kurir','pengiriman','penugasan','riwayat_status','laporan'];
if (!in_array($page, $allowed_pages)) $page = 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LogiTrack — Sistem Pengiriman Logistik</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🚛</text></svg>">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <?php require_once 'includes/sidebar.php'; ?>

  <div class="main-content">
    <?php require_once 'includes/topbar.php'; ?>

    <div class="content-wrapper">
      <?php
      $file = "pages/{$page}.php";
      if (file_exists($file)) require_once $file;
      else echo '<div class="alert alert-danger">⚠️ Halaman tidak ditemukan.</div>';
      ?>
    </div>
  </div>

  <script>
    // ─── Modal helpers ────────────────────────────────────────
    function openModal(id) {
      document.getElementById(id).style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
      document.getElementById(id).style.display = 'none';
      document.body.style.overflow = '';
    }
    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
      overlay.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
      });
    });
    // Close on ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(m => {
          if (m.style.display !== 'none') closeModal(m.id);
        });
      }
    });

    // ─── Sidebar Toggle ───────────────────────────────────────
    const menuBtn = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    if (menuBtn) {
      menuBtn.addEventListener('click', () => {
        sidebar.classList.toggle('open');
      });
    }

    // ─── editCustomer helper ──────────────────────────────────
    function editCustomer(data) {
      alert('Edit customer: ' + data.nama_customer + '\n(Silahkan implementasikan form edit)');
    }

    // ─── exportTable helper ───────────────────────────────────
    function exportTable() {
      alert('Fitur export akan mengunduh data dalam format Excel.');
    }

    // ─── Active nav highlight (jika navigasi ulang) ───────────
    const currentPage = new URLSearchParams(window.location.search).get('page') || 'dashboard';
    document.querySelectorAll('.nav-item').forEach(el => {
      if (el.getAttribute('href') === '?page=' + currentPage) {
        el.classList.add('active');
      }
    });

    // ─── Animate stat values ──────────────────────────────────
    function animateValue(el, start, end, dur) {
      let startTime = null;
      const step = (timestamp) => {
        if (!startTime) startTime = timestamp;
        const progress = Math.min((timestamp - startTime) / dur, 1);
        el.textContent = Math.floor(progress * (end - start) + start).toLocaleString('id-ID');
        if (progress < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    }
    // Animate stat numbers on load
    document.querySelectorAll('.stat-value').forEach(el => {
      const raw = el.textContent.replace(/[^0-9]/g,'');
      if (raw && parseInt(raw) > 10) {
        const end = parseInt(raw);
        // Only animate pure numbers
        if (!isNaN(end) && el.textContent.trim() === el.textContent.replace(/[^0-9.,]/g,'').trim()) {
          animateValue(el, 0, end, 800);
        }
      }
    });
  </script>

</body>
</html>
