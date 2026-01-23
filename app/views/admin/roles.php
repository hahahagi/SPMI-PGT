<?php require 'app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Manajemen Role (Jabatan)</h4>
    <button class="btn btn-primary-dark" data-bs-toggle="modal" data-bs-target="#modalTambahRole">
        <i class="bi bi-plus-circle-fill me-2"></i>Tambah Role
    </button>
</div>

<div class="card border-0 shadow-sm p-3">
    <div class="alert alert-info small border-0 bg-info bg-opacity-10 text-info">
        <i class="bi bi-info-circle me-2"></i> Role digunakan untuk mengelompokkan pengguna. Role 'admin' tidak dapat disesuaikan.
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-datatable w-100">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Role</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($roles as $r): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <span class="badge bg-secondary text-uppercase"><?= $r['nama_role'] ?></span>
                        </td>
                        <td>
                            <?php if ($r['nama_role'] != 'admin'): ?>
                                <a href="<?= $base_path ?>hapus_role/<?= $r['id_role'] ?>"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete(event, this.href)">
                                    <i class="bi bi-trash"></i>
                                </a>
                            <?php else: ?>
                                <span class="badge bg-light text-muted border">Default</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambahRole">
    <div class="modal-dialog">
        <form class="modal-content" action="<?= $base_path ?>simpan_role" method="POST">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Role Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold text-muted">Nama Role</label>
                    <input type="text" name="nama_role" class="form-control" required placeholder="Cth: wadir1">
                    <div class="form-text">Gunakan huruf kecil tanpa spasi (cth: kemahasiswaan)</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary-dark w-100">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<?php require 'app/views/layouts/footer.php'; ?>