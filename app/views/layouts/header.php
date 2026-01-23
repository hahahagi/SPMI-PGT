<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - SPMI Poltek GT' : 'SPMI Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f8;
        }

        .navbar-custom {
            background: #fff;
            height: 70px;
            border-bottom: 1px solid #e0e0e0;
            z-index: 1030;
        }

        .btn-primary-dark {
            background: #212529;
            color: #FFC107;
            border: none;
        }

        .btn-primary-dark:hover {
            background: #000;
            color: #fff;
        }

        /* Pastikan tabel mengambil lebar penuh dan tidak terpotong */
        .dataTables_wrapper {
            width: 100%;
        }

        table.dataTable {
            width: 100% !important;
            margin: 0 auto;
        }

        /* Memperbaiki jarak antar baris sidebar */
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #FFC107 !important;
        }

        /* Sidebar Styling */
        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                top: 70px;
                /* Height of navbar */
                bottom: 0;
                left: 0;
                z-index: 100;
                padding: 0;
                overflow-x: hidden;
                overflow-y: auto;
                /* Scrollable contents if viewport is shorter than content. */
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-custom fixed-top px-4 d-flex justify-content-between shadow-sm">
        <div class="fw-bold d-flex align-items-center">
            <img src="https://poltek-gt.ac.id/wp-content/uploads/2024/01/LOGO-FIX-BANGET-1-1-300x300.webp" height="50" class="me-2"> SPMI POLTEK-GT
        </div>
        <button class="navbar-toggler d-md-none collapsed" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="d-none d-md-flex align-items-center gap-3">
            <div class="text-end">
                <div class="fw-bold small mb-1"><?= $_SESSION['user']['nama_lengkap'] ?? 'Guest' ?></div>
                <div class="text-muted" style="font-size:0.7rem;"><?= strtoupper($_SESSION['user']['role'] ?? '') ?></div>
            </div>
            <a href="<?= $base_path ?>logout" class="btn btn-sm btn-outline-danger" title="Logout" onclick="confirmLogout(event, this.href)">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </nav>

    <div class="container-fluid" style="margin-top: 70px;">
        <div class="row">

            <?php require 'app/views/layouts/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 p-0 d-flex flex-column" style="min-height: calc(100vh - 70px); background: #f4f6f8;">
                <div class="container-fluid px-md-4 py-4 flex-grow-1">