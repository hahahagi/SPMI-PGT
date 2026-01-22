<?php require 'app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Manajemen Pengguna</h4>
    <button class="btn btn-primary-dark" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
        <i class="bi bi-person-plus-fill me-2"></i>Tambah User
    </button>
</div>

<div class="card border-0 shadow-sm p-3">
    <table class="table table-hover align-middle table-datatable">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Role (Jabatan)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($users as $u): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td class="fw-bold">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle border d-flex align-items-center justify-content-center me-2" style="width:35px; height:35px;">
                                <i class="bi bi-person-fill text-secondary"></i>
                            </div>
                            <?= $u['nama_lengkap'] ?>
                        </div>
                    </td>
                    <td><?= $u['username'] ?></td>
                    <td>
                        <?php
                        $badge = 'secondary';
                        if ($u['role'] == 'admin') $badge = 'dark';
                        if ($u['role'] == 'kaprodi') $badge = 'primary';
                        if ($u['role'] == 'keuangan') $badge = 'success';
                        ?>
                        <span class="badge bg-<?= $badge ?>"><?= strtoupper($u['role']) ?></span>
                    </td>
                    <td>
                        <?php if ($u['id_user'] != $_SESSION['user']['id_user']): ?>
                            <a href="index.php?act=hapus_user&id=<?= $u['id_user'] ?>"
                                class="btn btn-sm btn-outline-danger"
                                onclick="confirmDelete(event, this.href)">
                                <i class="bi bi-trash"></i>
                            </a>
                        <?php else: ?>
                            <span class="badge bg-light text-muted border">Anda</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalTambahUser">
    <div class="modal-dialog">
        <form class="modal-content" action="index.php?act=simpan_user" method="POST">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold text-muted">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required placeholder="Cth: Budi Santoso, M.T.">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted">Username (Untuk Login)</label>
                    <input type="text" name="username" class="form-control" required placeholder="Cth: kaprodi_tm">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="******">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted">Role / Jabatan</label>
                    <select name="role" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['nama_role'] ?>"><?= strtoupper($r['nama_role']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary-dark w-100">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<?php require 'app/views/layouts/footer.php'; ?>