<?php require 'app/views/layouts/header.php'; ?>

<!-- STATS CARDS -->
<div class="row mb-4 g-3">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Total User</div>
                    <h2 class="fw-bold mb-0 text-primary"><?= $stats['user'] ?></h2>
                </div>
                <i class="bi bi-people display-4 text-primary opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Permintaan</div>
                    <h2 class="fw-bold mb-0 text-info"><?= $stats['req'] ?></h2>
                </div>
                <i class="bi bi-file-earmark-text display-4 text-info opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Menunggu Cek</div>
                    <h2 class="fw-bold mb-0 text-warning"><?= $stats['pending'] ?></h2>
                </div>
                <i class="bi bi-clock-history display-4 text-warning opacity-25"></i>
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
                <i class="bi bi-exclamation-circle display-4 text-danger opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <h4 class="fw-bold mb-0">Kelola Permintaan Dokumen</h4>
    <button class="btn btn-primary-dark" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Buat Permintaan</button>
</div>

<div class="card border-0 shadow-sm p-3">
    <table class="table table-hover align-middle table-datatable w-100">
        <thead class="table-light">
            <tr>
                <th>Judul</th>
                <th>Tujuan</th>
                <th>Deadline</th>
                <th>Status File</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data_permintaan as $row): ?>
                <tr>
                    <td class="fw-bold"><?= $row['judul'] ?></td>
                    <td><span class="badge bg-secondary"><?= strtoupper($row['tujuan_role']) ?></span></td>
                    <td><?= $row['tanggal_deadline'] ?></td>
                    <td>
                        <?php
                        // LOGIKA PRIORITAS STATUS
                        if ($row['total_file'] == 0) {
                            echo "<span class='badge bg-light text-muted border'>Belum Ada File</span>";
                        } elseif ($row['revisi'] > 0) {
                            // Jika ada SATU saja file revisi, status jadi MERAH
                            echo "<span class='badge bg-danger'>Perlu Revisi (" . $row['revisi'] . ")</span>";
                        } elseif ($row['pending'] > 0) {
                            // Jika tidak ada revisi tapi ada yang menunggu
                            echo "<span class='badge bg-warning text-dark'>Menunggu Verifikasi (" . $row['pending'] . ")</span>";
                        } else {
                            // Jika semua file sudah valid
                            echo "<span class='badge bg-success'>Valid / Selesai</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            <a href="<?= $base_path ?>verifikasi/<?= $row['id_permintaan'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Periksa
                            </a>
                            <button class="btn btn-sm btn-outline-warning"
                                onclick="editPermintaan(this)"
                                data-id="<?= $row['id_permintaan'] ?>"
                                data-judul="<?= htmlspecialchars($row['judul']) ?>"
                                data-deskripsi="<?= htmlspecialchars($row['deskripsi']) ?>"
                                data-tujuan="<?= $row['tujuan_role'] ?>"
                                data-deadline="<?= $row['tanggal_deadline'] ?>"
                                data-bs-toggle="modal" data-bs-target="#modalEdit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <a href="<?= $base_path ?>hapus_permintaan/<?= $row['id_permintaan'] ?>" class="btn btn-sm btn-outline-danger" onclick="confirmDelete(event, this.href)">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form class="modal-content" action="<?= $base_path ?>simpan_permintaan" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Buat Permintaan</h5><button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label>Judul Dokumen</label><input type="text" name="judul" class="form-control" required></div>
                <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
                <div class="mb-3"><label>Tujuan Role</label>
                    <select name="tujuan" class="form-select">
                        <option value="kaprodi">Kaprodi</option>
                        <option value="keuangan">Keuangan</option>
                        <option value="akademik">Akademik</option>
                    </select>
                </div>
                <div class="mb-3"><label>Deadline</label><input type="date" name="deadline" class="form-control" required></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary-dark">Simpan</button></div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEdit">
    <div class="modal-dialog">
        <form class="modal-content" action="<?= $base_path ?>update_permintaan" method="POST">
            <input type="hidden" name="id_permintaan" id="edit_id">
            <div class="modal-header">
                <h5 class="modal-title">Edit Permintaan</h5><button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label>Judul Dokumen</label><input type="text" name="judul" id="edit_judul" class="form-control" required></div>
                <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" id="edit_deskripsi" class="form-control"></textarea></div>
                <div class="mb-3"><label>Tujuan Role</label>
                    <select name="tujuan" id="edit_tujuan" class="form-select">
                        <option value="kaprodi">Kaprodi</option>
                        <option value="keuangan">Keuangan</option>
                        <option value="akademik">Akademik</option>
                    </select>
                </div>
                <div class="mb-3"><label>Deadline</label><input type="date" name="deadline" id="edit_deadline" class="form-control" required></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary-dark">Update</button></div>
        </form>
    </div>
</div>

<script>
    function editPermintaan(btn) {
        const d = btn.dataset;
        document.getElementById('edit_id').value = d.id;
        document.getElementById('edit_judul').value = d.judul;
        document.getElementById('edit_deskripsi').value = d.deskripsi;
        document.getElementById('edit_tujuan').value = d.tujuan;
        document.getElementById('edit_deadline').value = d.deadline;
    }
</script>
<?php require 'app/views/layouts/footer.php'; ?>