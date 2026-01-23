<?php require 'app/views/layouts/header.php'; ?>

<div class="mb-4">
    <a href="<?= $base_path ?>dashboard" class="btn btn-light border btn-sm shadow-sm">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
    </a>
</div>


<div class="card border-0 shadow-sm p-3 mb-4"> <!-- DETAIL PERMINTAAN DI ATAS -->
    <div class="row align-items-center">
        <div class="col-md-9">
            <h6 class="text-uppercase text-muted fw-bold small mb-1">Detail Permintaan</h6>
            <h4 class="fw-bold mb-1"><?= $info['judul'] ?></h4>
            <p class="text-muted mb-0"><?= $info['deskripsi'] ?></p>
        </div>
        <div class="col-md-3 text-end border-start">
            <div class="mb-1">
                <span class="badge bg-primary"><?= strtoupper($info['tujuan_role']) ?></span>
            </div>
            <div>
                <small class="text-muted small d-block">DEADLINE</small>
                <span class="fs-5 fw-bold text-danger"><?= date('d M Y', strtotime($info['tanggal_deadline'])) ?></span>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm p-4"> <!-- TABEL FULL WIDTH -->
    <h5 class="fw-bold mb-4">Berkas Masuk (<?= count($files) ?> File)</h5>

    <?php if (empty($files)): ?>
        <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning text-center py-5">
            <i class="bi bi-exclamation-circle display-4 mb-3 d-block"></i>
            Belum ada file yang diupload oleh user.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-datatable w-100 border">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Judul Dokumen</th>
                        <th>File</th>
                        <th>Status</th>
                        <th style="width: 250px;">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($files as $f): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark"><?= $f['nama_lengkap'] ?></div>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <?= date('d M Y H:i', strtotime($f['tanggal_upload'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="fw-normal"><?= htmlspecialchars(!empty($f['judul']) ? $f['judul'] : '-') ?></div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" onclick="previewFile('<?= $base_path ?>serve_file?file=<?= urlencode($f['file_path']) ?>&mode=inline', '<?= htmlspecialchars($f['file_path'], ENT_QUOTES) ?>'); return false;" class="btn btn-outline-primary" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= $base_path ?>serve_file?file=<?= urlencode($f['file_path']) ?>&mode=download" class="btn btn-outline-success" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                                <div class="small text-muted mt-1 text-truncate" style="max-width: 120px;" title="<?= $f['file_path'] ?>">
                                    <?= $f['file_path'] ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($f['status_verifikasi'] == 'menunggu'): ?>
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                <?php elseif ($f['status_verifikasi'] == 'diterima'): ?>
                                    <span class="badge bg-success">Valid</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Revisi</span>
                                <?php endif; ?>

                                <?php if ($f['status_verifikasi'] == 'revisi' && !empty($f['catatan_admin'])): ?>
                                    <div class="mt-1 p-1 bg-danger bg-opacity-10 text-danger rounded small" style="font-size: 0.7rem;">
                                        <strong>Note:</strong> <?= $f['catatan_admin'] ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="<?= $base_path ?>proses_validasi" method="POST">
                                    <input type="hidden" name="id_pengumpulan" value="<?= $f['id_pengumpulan'] ?>">
                                    <input type="hidden" name="id_permintaan" value="<?= $info['id_permintaan'] ?>">

                                    <div class="mb-2">
                                        <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Catatan (Opsi jika revisi)..."><?= $f['catatan_admin'] ?></textarea>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <button type="submit" name="status" value="revisi" class="btn btn-sm btn-outline-danger flex-fill">
                                            <i class="bi bi-x-circle"></i> Revisi
                                        </button>
                                        <button type="submit" name="status" value="diterima" class="btn btn-sm btn-success flex-fill">
                                            <i class="bi bi-check-lg"></i> Valid
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<!-- No closing div for col-md-8 anymore -->
<!-- No closing div for row anymore -->

<?php require 'app/views/layouts/footer.php'; ?>