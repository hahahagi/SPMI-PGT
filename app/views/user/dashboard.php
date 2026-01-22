<?php require 'app/views/layouts/header.php'; ?>

<!-- USER STATS -->
<div class="row mb-4 g-4">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Total Tugas</div>
                    <h2 class="fw-bold mb-0 text-primary"><?= $stats['tugas'] ?></h2>
                </div>
                <i class="bi bi-list-task display-4 text-primary opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Selesai / Valid</div>
                    <h2 class="fw-bold mb-0 text-success"><?= $stats['valid'] ?></h2>
                </div>
                <i class="bi bi-check-circle display-4 text-success opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Sedang Diperiksa</div>
                    <h2 class="fw-bold mb-0 text-warning"><?= $stats['menunggu'] ?></h2>
                </div>
                <i class="bi bi-hourglass-split display-4 text-warning opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Perlu Revisi</div>
                    <h2 class="fw-bold mb-0 text-danger"><?= $stats['revisi'] ?></h2>
                </div>
                <i class="bi bi-exclamation-triangle display-4 text-danger opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<h4 class="fw-bold mb-4">Tagihan Dokumen Saya</h4>

<div class="card border-0 shadow-sm p-3">
    <table class="table table-hover align-middle table-datatable w-100">
        <thead class="table-light">
            <tr>
                <th>Judul Dokumen</th>
                <th>Deadline</th>
                <th>Status Terkini</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data_tugas as $t):
                // LOGIC: Filter file milik user ini untuk tugas ini
                // Kita cek array $data_files yang dikirim dari controller
                $myFiles = array_filter($data_files, function ($f) use ($t) {
                    return $f['id_permintaan'] == $t['id_permintaan'];
                });

                // Tentukan Status Dominan
                $statusBadge = "<span class='badge bg-secondary bg-opacity-25 text-secondary'>Belum Upload</span>";

                if (!empty($myFiles)) {
                    $hasRevisi = false;
                    $hasPending = false;
                    $allValid = true;

                    foreach ($myFiles as $mf) {
                        if ($mf['status_verifikasi'] == 'revisi') $hasRevisi = true;
                        if ($mf['status_verifikasi'] == 'menunggu') $hasPending = true;
                        if ($mf['status_verifikasi'] != 'diterima') $allValid = false;
                    }

                    if ($hasRevisi) {
                        $statusBadge = "<span class='badge bg-danger'>Revisi Diperlukan</span>";
                    } elseif ($hasPending) {
                        $statusBadge = "<span class='badge bg-warning text-dark'>Sedang Diperiksa</span>";
                    } elseif ($allValid) {
                        $statusBadge = "<span class='badge bg-success'>Valid / Selesai</span>";
                    }
                }
            ?>
                <tr>
                    <td>
                        <div class="fw-bold"><?= $t['judul'] ?></div>
                        <small class="text-muted"><?= $t['deskripsi'] ?></small>
                    </td>
                    <td><?= $t['tanggal_deadline'] ?></td>
                    <td>
                        <?= $statusBadge ?>
                        <?php if (!empty($myFiles)): ?>
                            <div class="small text-muted mt-1" style="font-size: 0.7rem;">
                                (<?= count($myFiles) ?> file diupload)
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary-dark" data-bs-toggle="modal" data-bs-target="#modal<?= $t['id_permintaan'] ?>">
                            <i class="bi bi-cloud-upload me-2"></i> Kelola
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php foreach ($data_tugas as $t):
    $myFiles = array_filter($data_files, function ($f) use ($t) {
        return $f['id_permintaan'] == $t['id_permintaan'];
    });
?>
    <div class="modal fade" id="modal<?= $t['id_permintaan'] ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><?= $t['judul'] ?></h5><button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- LIST FILE -->
                    <div class="bg-light p-3 rounded mb-3 border">
                        <h6 class="small fw-bold text-muted mb-2">File Terupload:</h6>
                        <?php if (empty($myFiles)): ?>
                            <i class='text-muted small'>Belum ada file.</i>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm small bg-white mb-0 table-datatable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Judul Dokumen</th>
                                            <th>File</th>
                                            <th>Status</th>
                                            <th class="text-center" style="width:50px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($myFiles as $f): ?>
                                            <tr>
                                                <td><?= htmlspecialchars(!empty($f['judul']) ? $f['judul'] : 'Dokumen') ?></td>
                                                <td>
                                                    <a href="#" onclick="previewFile('serve_file?file=<?= $f['file_path'] ?>&mode=inline', '<?= $f['file_path'] ?>'); return false;" class="text-decoration-none">
                                                        <?= $f['file_path'] ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $f['status_verifikasi'] == 'diterima' ? 'success' : ($f['status_verifikasi'] == 'revisi' ? 'danger' : 'warning') ?>">
                                                        <?= strtoupper($f['status_verifikasi']) ?>
                                                    </span>
                                                    <?php if ($f['status_verifikasi'] == 'revisi'): ?>
                                                        <div class="text-danger mt-1 fst-italic" style="font-size:0.75em;">
                                                            Revisi: <?= $f['catatan_admin'] ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($f['status_verifikasi'] != 'diterima'): ?>
                                                        <a href="hapus_file/<?= $f['id_pengumpulan'] ?>" class="btn btn-sm btn-outline-danger py-0" onclick="confirmDelete(event, this.href)">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- UPLOAD FORM -->
                    <form action="upload" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_permintaan" value="<?= $t['id_permintaan'] ?>">
                        <label class="form-label small fw-bold">Upload Dokumen (Bisa Banyak)</label>

                        <div id="upload-container-<?= $t['id_permintaan'] ?>">
                            <!-- Default Row -->
                            <div class="row g-2 mb-2 upload-row">
                                <div class="col-6">
                                    <input type="text" name="judul_dokumen[]" class="form-control form-control-sm" placeholder="Judul Dokumen (Misal: BAB 1)" required>
                                </div>
                                <div class="col-6">
                                    <input type="file" name="berkas[]" class="form-control form-control-sm" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addUploadRow(<?= $t['id_permintaan'] ?>)">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Baris Upload
                            </button>
                        </div>

                        <button type="submit" class="btn btn-primary-dark w-100">Kirim Semua</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php require 'app/views/layouts/footer.php'; ?>
<script>
    function addUploadRow(id) {
        const container = document.getElementById('upload-container-' + id);
        const newRow = document.createElement('div');
        newRow.className = 'row g-2 mb-2 upload-row';
        newRow.innerHTML = `
        <div class="col-5">
            <input type="text" name="judul_dokumen[]" class="form-control form-control-sm" placeholder="Judul Dokumen" required>
        </div>
        <div class="col-6">
            <input type="file" name="berkas[]" class="form-control form-control-sm" required>
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-sm btn-danger w-100" onclick="this.closest('.upload-row').remove()">x</button>
        </div>
    `;
        container.appendChild(newRow);
    }
</script>