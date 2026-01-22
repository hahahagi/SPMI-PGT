<?php require 'app/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <h4 class="fw-bold mb-4">Profil Saya</h4>

        <div class="card border-0 shadow-sm p-4">
            <div class="text-center mb-4">
                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 fw-bold display-4 shadow-sm"
                    style="width: 80px; height: 80px;">
                    <?= substr($_SESSION['user']['nama_lengkap'], 0, 1) ?>
                </div>
                <h5 class="fw-bold"><?= $_SESSION['user']['nama_lengkap'] ?></h5>
                <span class="badge bg-secondary text-uppercase"><?= $_SESSION['user']['role'] ?></span>
            </div>

            <hr>

            <form action="index.php?act=update_profile" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= $_SESSION['user']['nama_lengkap'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Username (Login)</label>
                    <input type="text" name="username" class="form-control" value="<?= $_SESSION['user']['username'] ?>" required>
                </div>

                <div class="alert alert-light border small text-muted">
                    <i class="bi bi-info-circle me-1"></i> Kosongkan password jika tidak ingin mengubahnya.
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Password Baru</label>
                    <div class="input-group">
                        <input type="password" name="password" id="profilePassword" class="form-control" placeholder="******">
                        <button class="btn btn-outline-secondary" type="button" onclick="toggleProfilePassword()">
                            <i class="bi bi-eye" id="iconProfilePassword"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary-dark">
                        <i class="bi bi-save me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleProfilePassword() {
        var x = document.getElementById("profilePassword");
        var icon = document.getElementById("iconProfilePassword");
        if (x.type === "password") {
            x.type = "text";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        } else {
            x.type = "password";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        }
    }
</script>

<?php require 'app/views/layouts/footer.php'; ?>