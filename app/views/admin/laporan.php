<?php require 'app/views/layouts/header.php'; ?>

<!-- PRINT HEADER -->
<div class="d-none d-print-block text-center pb-3 mb-4 border-bottom">
    <img src="https://poltek-gt.ac.id/wp-content/uploads/2024/01/LOGO-FIX-BANGET-1-1-300x300.webp" height="80" class="mb-2">
    <h4 class="fw-bold mb-0">LAPORAN REKAPITULASI DOKUMEN SPMI</h4>
    <small class="text-muted">Politeknik Gajah Tunggal</small>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 hide-print">
    <h4 class="fw-bold mb-0">Laporan Rekapitulasi Dokumen</h4>
    <button onclick="window.print()" class="btn btn-outline-dark btn-sm">
        <i class="bi bi-printer me-2"></i>Cetak Laporan
    </button>
</div>

<!-- INFO CARDS -->
<div class="row mb-4 summary-cards">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Total User</div>
                    <h2 class="fw-bold mb-0 text-primary"><?= count($laporan) ?></h2>
                </div>
                <i class="bi bi-people display-4 text-primary opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Dokumen Valid</div>
                    <h2 class="fw-bold mb-0 text-success"><?= array_sum(array_column($laporan, 'valid')) ?></h2>
                </div>
                <i class="bi bi-check-circle display-4 text-success opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Menunggu Validasi</div>
                    <h2 class="fw-bold mb-0 text-warning"><?= array_sum(array_column($laporan, 'menunggu')) ?></h2>
                </div>
                <i class="bi bi-clock-history display-4 text-warning opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Perlu Revisi</div>
                    <h2 class="fw-bold mb-0 text-danger"><?= array_sum(array_column($laporan, 'revisi')) ?></h2>
                </div>
                <i class="bi bi-exclamation-circle display-4 text-danger opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-bordered align-middle w-100">
            <thead class="table-light text-center">
                <tr>
                    <th rowspan="2" class="align-middle">No</th>
                    <th rowspan="2" class="align-middle">Nama Pengguna</th>
                    <th rowspan="2" class="align-middle">Role / Jabatan</th>
                    <th colspan="4">Status Dokumen</th>
                </tr>
                <tr>
                    <th class="text-primary">Total Upload</th>
                    <th class="text-warning">Menunggu</th>
                    <th class="text-danger">Revisi</th>
                    <th class="text-success">Valid</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($laporan)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data user.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1;
                    foreach ($laporan as $row): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="fw-bold"><?= $row['nama_lengkap'] ?></td>
                            <td><span class="badge bg-light text-dark border border-secondary text-uppercase"><?= $row['role'] ?></span></td>
                            <td class="text-center"><?= $row['total_upload'] ?></td>
                            <td class="text-center fw-bold text-warning"><?= $row['menunggu'] > 0 ? $row['menunggu'] : '-' ?></td>
                            <td class="text-center fw-bold text-danger"><?= $row['revisi'] > 0 ? $row['revisi'] : '-' ?></td>
                            <td class="text-center fw-bold text-success"><?= $row['valid'] > 0 ? $row['valid'] : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <p class="text-muted small mt-2">
        * Data diambil per tanggal: <?= date('d M Y H:i') ?>
    </p>
</div>

<style>
    @media print {
        @page {
            size: A4;
            margin: 10mm;
        }

        .hide-print,
        .sidebar,
        .navbar,
        .btn {
            display: none !important;
        }

        .main-content,
        body,
        html {
            background: #fff !important;
            font-size: 11pt;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .container-fluid,
        .row,
        .col-md-9 {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* GRID KARTU SUMMARY */
        .summary-cards {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 12px !important;
            /* Jarak antar kartu */
            margin: 0 !important;
            /* Reset margin negatif row */
            margin-bottom: 20px !important;
            width: 100% !important;
        }

        .summary-cards .col-md-3 {
            width: 100% !important;
            max-width: none !important;
            flex: none !important;
            padding: 0 !important;
        }

        .summary-cards .card {
            border: 1px solid #eee !important;
            /* Border halus */
            box-shadow: none !important;
            padding: 15px !important;
            height: 100% !important;
            /* Tinggi seragam */
        }

        /* Paksa Warna Background */
        .bg-primary {
            background-color: #0d6efd !important;
            color: white !important;
        }

        .bg-success {
            background-color: #198754 !important;
            color: white !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
            color: white !important;
        }

        .text-white-50 {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        /* TABEL */
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 20px;
            font-size: 10pt;
        }

        .table th {
            background-color: #f8f9fa !important;
            color: #000 !important;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6 !important;
            padding: 8px !important;
        }

        .badge {
            border: 1px solid #ccc;
            padding: 4px 8px;
        }
    }
</style>

<?php require 'app/views/layouts/footer.php'; ?>