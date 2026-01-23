<?php
// --- USER ---
function cek_login($koneksi, $user)
{
    // Cari user berdasarkan username
    $user = mysqli_real_escape_string($koneksi, $user);
    $q = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$user'");
    return mysqli_fetch_assoc($q);
}

// --- ADMIN: PERMINTAAN ---
function get_all_permintaan($koneksi)
{
    // KITA TAMBAHKAN LOGIC PENGHITUNG 'REVISI' DAN 'VALID'
    $sql = "SELECT p.*, 
            (SELECT COUNT(*) FROM pengumpulan g WHERE g.id_permintaan = p.id_permintaan) as total_file,
            (SELECT COUNT(*) FROM pengumpulan g WHERE g.id_permintaan = p.id_permintaan AND g.status_verifikasi='menunggu') as pending,
            (SELECT COUNT(*) FROM pengumpulan g WHERE g.id_permintaan = p.id_permintaan AND g.status_verifikasi='revisi') as revisi,
            (SELECT COUNT(*) FROM pengumpulan g WHERE g.id_permintaan = p.id_permintaan AND g.status_verifikasi='diterima') as valid
            FROM permintaan p ORDER BY created_at DESC";

    return mysqli_fetch_all(mysqli_query($koneksi, $sql), MYSQLI_ASSOC);
}

function tambah_permintaan($koneksi, $judul, $desc, $tujuan, $tgl)
{
    $judul = mysqli_real_escape_string($koneksi, $judul);
    $desc = mysqli_real_escape_string($koneksi, $desc);
    return mysqli_query($koneksi, "INSERT INTO permintaan (judul, deskripsi, tujuan_role, tanggal_deadline) VALUES ('$judul', '$desc', '$tujuan', '$tgl')");
}

function update_permintaan_db($koneksi, $id, $judul, $desc, $tujuan, $tgl)
{
    $judul = mysqli_real_escape_string($koneksi, $judul);
    $desc = mysqli_real_escape_string($koneksi, $desc);
    $tujuan = mysqli_real_escape_string($koneksi, $tujuan);
    $tgl = mysqli_real_escape_string($koneksi, $tgl);

    return mysqli_query($koneksi, "UPDATE permintaan SET judul='$judul', deskripsi='$desc', tujuan_role='$tujuan', tanggal_deadline='$tgl' WHERE id_permintaan='$id'");
}

function hapus_permintaan_db($koneksi, $id)
{
    return mysqli_query($koneksi, "DELETE FROM permintaan WHERE id_permintaan='$id'");
}

// --- USER: TUGAS & UPLOAD ---
function get_tugas_user($koneksi, $role)
{
    $role = mysqli_real_escape_string($koneksi, $role);
    return mysqli_fetch_all(mysqli_query($koneksi, "SELECT * FROM permintaan WHERE tujuan_role='$role' ORDER BY tanggal_deadline ASC"), MYSQLI_ASSOC);
}

function get_files_user($koneksi, $id_user)
{
    return mysqli_fetch_all(mysqli_query($koneksi, "SELECT * FROM pengumpulan WHERE id_user='$id_user'"), MYSQLI_ASSOC);
}

function simpan_file($koneksi, $id_minta, $id_user, $path, $judul)
{
    $judul = mysqli_real_escape_string($koneksi, $judul);
    return mysqli_query($koneksi, "INSERT INTO pengumpulan (id_permintaan, id_user, file_path, judul) VALUES ('$id_minta', '$id_user', '$path', '$judul')");
}

function hapus_file_db($koneksi, $id)
{
    return mysqli_query($koneksi, "DELETE FROM pengumpulan WHERE id_pengumpulan='$id'");
}

function get_file_detail($koneksi, $id)
{
    $q = mysqli_query($koneksi, "SELECT * FROM pengumpulan WHERE id_pengumpulan='$id'");
    return mysqli_fetch_assoc($q);
}

function update_file_reupload($koneksi, $id, $path)
{
    return mysqli_query($koneksi, "UPDATE pengumpulan SET file_path='$path', status_verifikasi='menunggu' WHERE id_pengumpulan='$id'");
}

// --- ADMIN: VERIFIKASI ---
function get_detail_verifikasi($koneksi, $id)
{
    $info = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM permintaan WHERE id_permintaan='$id'"));
    $files = mysqli_fetch_all(mysqli_query($koneksi, "SELECT p.*, u.nama_lengkap FROM pengumpulan p JOIN users u ON p.id_user=u.id_user WHERE p.id_permintaan='$id'"), MYSQLI_ASSOC);
    return ['info' => $info, 'files' => $files];
}

function update_verifikasi($koneksi, $id, $status, $catatan)
{
    $catatan = mysqli_real_escape_string($koneksi, $catatan);
    return mysqli_query($koneksi, "UPDATE pengumpulan SET status_verifikasi='$status', catatan_admin='$catatan' WHERE id_pengumpulan='$id'");
}

// --- MANAJEMEN USER (ADMIN) ---
function get_all_users($koneksi)
{
    // Ambil semua user kecuali Admin pusat (opsional, biar admin gak kehapus sendiri)
    // Atau ambil semua saja
    return mysqli_fetch_all(mysqli_query($koneksi, "SELECT * FROM users ORDER BY role ASC, nama_lengkap ASC"), MYSQLI_ASSOC);
}

function tambah_user($koneksi, $nama, $user, $pass, $role)
{
    $nama = mysqli_real_escape_string($koneksi, $nama);
    $user = mysqli_real_escape_string($koneksi, $user);
    $pass = md5($pass); // Kita samakan pakai MD5
    $role = mysqli_real_escape_string($koneksi, $role);

    return mysqli_query($koneksi, "INSERT INTO users (nama_lengkap, username, password, role) VALUES ('$nama', '$user', '$pass', '$role')");
}

function hapus_user_db($koneksi, $id)
{
    return mysqli_query($koneksi, "DELETE FROM users WHERE id_user='$id'");
}

// --- MANAJEMEN ROLE ---
function get_all_roles($koneksi)
{
    return mysqli_fetch_all(mysqli_query($koneksi, "SELECT * FROM roles ORDER BY nama_role ASC"), MYSQLI_ASSOC);
}

function tambah_role($koneksi, $judul)
{
    $judul = mysqli_real_escape_string($koneksi, strtolower($judul));
    return mysqli_query($koneksi, "INSERT INTO roles (nama_role) VALUES ('$judul')");
}

function hapus_role_db($koneksi, $id)
{
    return mysqli_query($koneksi, "DELETE FROM roles WHERE id_role='$id'");
}

// --- LAPORAN ---
function get_rekap_laporan($koneksi)
{
    $query = "
        SELECT 
            u.nama_lengkap, 
            u.role, 
            COUNT(p.id_pengumpulan) as total_upload,
            SUM(CASE WHEN p.status_verifikasi = 'diterima' THEN 1 ELSE 0 END) as valid,
            SUM(CASE WHEN p.status_verifikasi = 'revisi' THEN 1 ELSE 0 END) as revisi,
            SUM(CASE WHEN p.status_verifikasi = 'menunggu' THEN 1 ELSE 0 END) as menunggu
        FROM users u
        LEFT JOIN pengumpulan p ON u.id_user = p.id_user
        WHERE u.role != 'admin'
        GROUP BY u.id_user
        ORDER BY u.role ASC, u.nama_lengkap ASC
    ";
    return mysqli_fetch_all(mysqli_query($koneksi, $query), MYSQLI_ASSOC);
}

// --- PROFILE ---
function update_profile_db($koneksi, $id, $nama, $username, $password = null)
{
    $nama = mysqli_real_escape_string($koneksi, $nama);
    $username = mysqli_real_escape_string($koneksi, $username);

    if ($password) {
        $password = md5($password);
        $query = "UPDATE users SET nama_lengkap='$nama', username='$username', password='$password' WHERE id_user='$id'";
    } else {
        $query = "UPDATE users SET nama_lengkap='$nama', username='$username' WHERE id_user='$id'";
    }

    return mysqli_query($koneksi, $query);
}

// --- DASHBOARD STATS ---
function get_dashboard_stats_admin($koneksi)
{
    $total_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM users WHERE role != 'admin'"))['c'];
    $total_req = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM permintaan"))['c'];
    $pending = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pengumpulan WHERE status_verifikasi='menunggu'"))['c'];
    $revisi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pengumpulan WHERE status_verifikasi='revisi'"))['c'];
    return ['user' => $total_user, 'req' => $total_req, 'pending' => $pending, 'revisi' => $revisi];
}

function get_dashboard_stats_user($koneksi, $id_user, $role)
{
    $total_tugas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM permintaan WHERE tujuan_role='$role'"))['c'];

    $valid = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pengumpulan WHERE id_user='$id_user' AND status_verifikasi='diterima'"))['c'];
    $revisi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pengumpulan WHERE id_user='$id_user' AND status_verifikasi='revisi'"))['c'];
    $menunggu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pengumpulan WHERE id_user='$id_user' AND status_verifikasi='menunggu'"))['c'];

    return ['tugas' => $total_tugas, 'valid' => $valid, 'revisi' => $revisi, 'menunggu' => $menunggu];
}
