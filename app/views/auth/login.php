<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPMI Poltek GT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }

        .bg-image {
            background-image: url('https://poltek-gt.ac.id/wp-content/uploads/2025/10/kp2sh2klr8xqzw1itoty-1024x415.webp');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .bg-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            /* Overlay Gelap */
        }

        .login-wrap {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .form-control:focus {
            border-color: #FFC107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .btn-login {
            background: #212529;
            color: #FFC107;
            font-weight: bold;
            border: none;
            padding: 12px;
        }

        .btn-login:hover {
            background: #000;
            color: #fff;
            transform: translateY(-2px);
            transition: 0.3s;
        }
    </style>
</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-lg-7 d-none d-lg-block bg-image">
                <div class="d-flex h-100 align-items-end p-5" style="position: relative; z-index: 2;">
                    <div class="text-white">
                        <h1 class="fw-bold text-warning">SPMI SYSTEM</h1>
                        <p class="fs-5">Sistem Penjaminan Mutu Internal<br>Politeknik Gajah Tunggal</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-flex flex-column align-items-center justify-content-between bg-white h-100 p-0">

                <!-- Gambar Gedung (Mobile Only) -->
                <div class="d-lg-none w-100 position-relative overflow-hidden" style="height: 35vh; min-height: 200px;">
                    <div style="background-image: url('https://poltek-gt.ac.id/wp-content/uploads/2025/10/kp2sh2klr8xqzw1itoty-1024x415.webp'); background-size: cover; background-position: center; width: 100%; height: 100%; transform: scale(1.1);"></div>
                </div>

                <div class="login-wrap mx-auto flex-grow-1 d-flex flex-column justify-content-center">

                    <div class="mb-4 text-center">
                        <img src="https://poltek-gt.ac.id/wp-content/uploads/2024/01/LOGO-FIX-BANGET-1-1-300x300.webp" alt="Logo Poltek GT" height="100" class="mb-3">
                        <h3 class="fw-bold fs-4">Selamat Datang</h3>
                        <p class="text-muted small">Silakan login menggunakan akun Anda.</p>
                    </div>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger py-2 small"><?= $error ?></div>
                    <?php endif; ?>
                    <form action="proses_login" method="POST">
                        <div class="mb-3">
                            <label class="small fw-bold text-muted">USERNAME</label>
                            <input type="text" name="username" class="form-control" required placeholder="masukkan username Anda">
                        </div>
                        <div class="mb-4">
                            <label class="small fw-bold text-muted">PASSWORD</label>
                            <div class="input-group">
                                <input type="password" name="password" id="inputPassword" class="form-control border-end-0" required placeholder="******">
                                <span class="input-group-text bg-white border-start-0" role="button" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="iconPassword"></i>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-login w-100 rounded-3 shadow-sm">MASUK SEKARANG</button>
                    </form>
                </div>
                <div class="text-center pb-3 text-muted small w-100">&copy; 2026 Unit Penjaminan Mutu</div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            var x = document.getElementById("inputPassword");
            var icon = document.getElementById("iconPassword");
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
</body>

</html>