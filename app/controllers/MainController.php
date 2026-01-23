<?php
require_once 'app/models/MasterModel.php';

function do_login($koneksi)
{
    $user = $_POST['username'];
    $pass = md5($_POST['password']); // Menggunakan MD5 sesuai database

    $data = cek_login($koneksi, $user);

    if ($data && $data['password'] == $pass) {
        $_SESSION['user'] = $data;
        header("Location: " . BASE_URL . "dashboard?status=login_sukses");
    } else {
        $error = "Username atau Password Salah!";
        require 'app/views/auth/login.php';
    }
}

function act_simpan_permintaan($koneksi)
{
    tambah_permintaan($koneksi, $_POST['judul'], $_POST['deskripsi'], $_POST['tujuan'], $_POST['deadline']);
    header("Location: " . BASE_URL . "dashboard?status=sukses");
}

function act_update_permintaan($koneksi)
{
    update_permintaan_db($koneksi, $_POST['id_permintaan'], $_POST['judul'], $_POST['deskripsi'], $_POST['tujuan'], $_POST['deadline']);
    header("Location: " . BASE_URL . "dashboard?status=update_sukses");
}

function act_upload($koneksi)
{
    $id_user = $_SESSION['user']['id_user'];
    $id_permintaan = $_POST['id_permintaan'];

    // Buat folder uploads jika belum ada
    if (!file_exists('uploads')) mkdir('uploads', 0777, true);

    $juduls = $_POST['judul_dokumen']; // Array of titles

    // Check if files are uploaded
    if (isset($_FILES['berkas'])) {
        foreach ($_FILES['berkas']['name'] as $i => $name) {
            if ($_FILES['berkas']['error'][$i] == 0) {

                // Get corresponding title
                $judul_doc = isset($juduls[$i]) ? $juduls[$i] : $name;

                $newName = uniqid() . "_" . $name;
                if (move_uploaded_file($_FILES['berkas']['tmp_name'][$i], "uploads/" . $newName)) {
                    simpan_file($koneksi, $id_permintaan, $id_user, $newName, $judul_doc);
                }
            }
        }
    }
    header("Location: " . BASE_URL . "dashboard?status=upload_sukses&open_modal=" . $id_permintaan);
}

function act_hapus_file($koneksi)
{
    $file = get_file_detail($koneksi, $_GET['id']);
    if ($file) {
        if (file_exists("uploads/" . $file['file_path'])) unlink("uploads/" . $file['file_path']);
        hapus_file_db($koneksi, $_GET['id']);
        header("Location: " . BASE_URL . "dashboard?status=hapus_sukses&open_modal=" . $file['id_permintaan']);
    } else {
        header("Location: " . BASE_URL . "dashboard");
    }
}

function act_reupload($koneksi)
{
    $id_pengumpulan = $_POST['id_pengumpulan'];
    $file_info = get_file_detail($koneksi, $id_pengumpulan);

    if ($file_info && isset($_FILES['berkas']) && $_FILES['berkas']['error'] == 0) {
        // Hapus file lama fisik
        if (file_exists("uploads/" . $file_info['file_path'])) {
            unlink("uploads/" . $file_info['file_path']);
        }

        // Upload file baru
        $name = $_FILES['berkas']['name'];
        $newName = uniqid() . "_" . $name;
        if (move_uploaded_file($_FILES['berkas']['tmp_name'], "uploads/" . $newName)) {
            update_file_reupload($koneksi, $id_pengumpulan, $newName);
            header("Location: " . BASE_URL . "dashboard?status=upload_sukses&open_modal=" . $file_info['id_permintaan']);
        } else {
            header("Location: " . BASE_URL . "dashboard?status=gagal&msg=Gagal upload file baru");
        }
    } else {
        header("Location: " . BASE_URL . "dashboard?status=gagal&msg=File tidak valid");
    }
}

function act_validasi($koneksi)
{
    update_verifikasi($koneksi, $_POST['id_pengumpulan'], $_POST['status'], $_POST['catatan']);
    header("Location: " . BASE_URL . "verifikasi/" . $_POST['id_permintaan'] . "?status=sukses");
}

// --- USER MANAGEMENT ---
function act_simpan_user($koneksi)
{
    // Cek username kembar dulu (Validasi sederhana)
    $cek = cek_login($koneksi, $_POST['username']);
    if ($cek) {
        header("Location: " . BASE_URL . "data_user?status=gagal_username");
        exit;
    }

    tambah_user($koneksi, $_POST['nama'], $_POST['username'], $_POST['password'], $_POST['role']);
    header("Location: " . BASE_URL . "data_user?status=sukses");
}

function act_hapus_user($koneksi)
{
    // Cegah hapus diri sendiri
    if ($_GET['id'] == $_SESSION['user']['id_user']) {
        echo "<script>alert('Dilarang menghapus akun sendiri!'); window.location='" . BASE_URL . "data_user';</script>";
        return;
    }

    hapus_user_db($koneksi, $_GET['id']);
    header("Location: " . BASE_URL . "data_user?status=hapus_sukses");
}

function act_simpan_role($koneksi)
{
    tambah_role($koneksi, $_POST['nama_role']);
    header("Location: " . BASE_URL . "data_role?status=sukses");
}

function act_hapus_role($koneksi)
{
    hapus_role_db($koneksi, $_GET['id']);
    header("Location: " . BASE_URL . "data_role?status=hapus_sukses");
}

function act_update_profile($koneksi)
{
    $id = $_SESSION['user']['id_user'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? $_POST['password'] : null;

    // Cek jika ganti username, pastikan belum ada yang pakai
    if ($username != $_SESSION['user']['username']) {
        $cek = cek_login($koneksi, $username);
        if ($cek) {
            header("Location: " . BASE_URL . "profile?status=gagal_username");
            exit;
        }
    }

    if (update_profile_db($koneksi, $id, $nama, $username, $password)) {
        // Update Session agar nama user di header berubah
        $_SESSION['user']['nama_lengkap'] = $nama;
        $_SESSION['user']['username'] = $username;

        header("Location: " . BASE_URL . "profile?status=sukses");
    } else {
        header("Location: " . BASE_URL . "profile?status=gagal");
    }
}

function act_serve_file()
{
    $file_name = isset($_GET['file']) ? basename($_GET['file']) : '';
    $mode = isset($_GET['mode']) ? $_GET['mode'] : 'inline'; // inline = view, attachment = download
    $file_path = 'uploads/' . $file_name;

    // Security Check: Prevent accessing files outside uploads
    if (strpos($file_path, '..') !== false) {
        die("Access Denied");
    }

    if (!empty($file_name) && file_exists($file_path)) {
        // Deteksi MIME Type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_path);
        finfo_close($finfo);

        // Header untuk file
        header("Content-Type: " . $mime_type);
        header("Content-Length: " . filesize($file_path));

        if ($mode == 'download') {
            header("Content-Disposition: attachment; filename=\"" . $file_name . "\"");
        } else {
            header("Content-Disposition: inline; filename=\"" . $file_name . "\"");
        }

        // Bersihkan output buffer agar file tidak korup
        while (ob_get_level()) ob_end_clean();
        flush();
        readfile($file_path);
        exit;
    } else {
        http_response_code(404);
        die("File tidak ditemukan.");
    }
}
