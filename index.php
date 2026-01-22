<?php
session_start();
require_once 'app/config/koneksi.php';
require_once 'app/controllers/MainController.php';

// URL Routing Logic
$base_path = '/spmi/';
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove base path from the path
if (strpos($path, $base_path) === 0) {
    $path = substr($path, strlen($base_path));
}

// Remove trailing slash
$path = rtrim($path, '/');

// Parse segments
$segments = explode('/', $path);
$route = $segments[0] ?? '';

// Default route
if ($route === '' || $route === 'index.php') {
    // If param act is set (legacy support), use it, otherwise login
    $route = isset($_GET['act']) ? $_GET['act'] : 'login';
}

$act = $route;

// Map segments to GET params for specific actions
if ($act == 'verifikasi' && isset($segments[1])) $_GET['id'] = $segments[1];
if ($act == 'hapus_permintaan' && isset($segments[1])) $_GET['id'] = $segments[1];
if ($act == 'hapus_file' && isset($segments[1])) $_GET['id'] = $segments[1];
if ($act == 'hapus_user' && isset($segments[1])) $_GET['id'] = $segments[1];
if ($act == 'hapus_role' && isset($segments[1])) $_GET['id'] = $segments[1];

switch ($act) {
    // --- AUTH ---
    case 'login':
        require 'app/views/auth/login.php';
        break;
    case 'proses_login':
        do_login($koneksi);
        break;
    case 'logout':
        session_destroy();
        header("Location: login");
        break;

    // --- DASHBOARD ---
    case 'dashboard':
        if (!isset($_SESSION['user'])) {
            header("Location: login");
            exit;
        }

        if ($_SESSION['user']['role'] == 'admin') {
            $stats = get_dashboard_stats_admin($koneksi);
            $data_permintaan = get_all_permintaan($koneksi);
            $page_title = 'Dashboard Admin';
            require 'app/views/admin/dashboard.php';
        } else {
            $stats = get_dashboard_stats_user($koneksi, $_SESSION['user']['id_user'], $_SESSION['user']['role']);
            $data_tugas = get_tugas_user($koneksi, $_SESSION['user']['role']);
            $data_files = get_files_user($koneksi, $_SESSION['user']['id_user']);
            $page_title = 'Dashboard User';
            require 'app/views/user/dashboard.php';
        }
        break;

    // --- ADMIN: PERMINTAAN ---
    case 'simpan_permintaan':
        act_simpan_permintaan($koneksi);
        break;
    case 'update_permintaan':
        act_update_permintaan($koneksi);
        break;
    case 'hapus_permintaan':
        hapus_permintaan_db($koneksi, $_GET['id']);
        header("Location: dashboard");
        break;

    // --- ADMIN: VERIFIKASI (HALAMAN) ---
    case 'verifikasi':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') header("Location: login");

        $id_permintaan = $_GET['id'] ?? null;
        if (!$id_permintaan) {
            header("Location: dashboard");
            exit;
        }

        $data = get_detail_verifikasi($koneksi, $id_permintaan);

        // Cek jika data tidak ditemukan (misal ID ngawur atau terhapus)
        if (!$data['info']) {
            header("Location: dashboard?status=gagal&msg=Permintaan tidak ditemukan");
            exit;
        }

        $info = $data['info'];
        $files = $data['files'];
        $page_title = 'Verifikasi Dokumen';
        require 'app/views/admin/verifikasi.php';
        break;

    // --- ADMIN: PROSES VALIDASI (AKSI TOMBOL) ---
    // [!!!] INI YANG TADI HILANG, MAKANYA BALIK KE LOGIN [!!!]
    case 'proses_validasi':
        act_validasi($koneksi);
        break;

    // --- USER: UPLOAD & HAPUS ---
    case 'upload':
        act_upload($koneksi);
        break;
    case 'hapus_file':
        act_hapus_file($koneksi);
        break;

    // --- MANAJEMEN USER ---
    case 'data_user':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') header("Location: login");
        $users = get_all_users($koneksi);
        $roles = get_all_roles($koneksi);
        $page_title = 'Manajemen Pengguna';
        require 'app/views/admin/users.php';
        break;

    case 'simpan_user':
        act_simpan_user($koneksi);
        break;
    case 'hapus_user':
        act_hapus_user($koneksi);
        break;

    // --- MANAJEMEN ROLE ---
    case 'data_role':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') header("Location: login");
        $roles = get_all_roles($koneksi);
        $page_title = 'Manajemen Role';
        require 'app/views/admin/roles.php';
        break;
    case 'simpan_role':
        act_simpan_role($koneksi);
        break;
    case 'hapus_role':
        act_hapus_role($koneksi);
        break;

    // --- LAPORAN ADMIN ---
    case 'laporan':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') header("Location: login");
        $laporan = get_rekap_laporan($koneksi);
        $page_title = 'Laporan Rekapitulasi';
        require 'app/views/admin/laporan.php';
        break;

    // --- PROFILE USER ---
    case 'profile':
        if (!isset($_SESSION['user'])) header("Location: login");
        $page_title = 'Profil Saya';
        require 'app/views/profile.php';
        break;
    case 'update_profile':
        if (!isset($_SESSION['user'])) header("Location: login");
        act_update_profile($koneksi);
        break;

    // --- SECURE FILE ACCESS ---
    case 'serve_file':
        act_serve_file();
        break;

    // --- DEFAULT ---
    default:
        require 'app/views/auth/login.php';
        break;
}
