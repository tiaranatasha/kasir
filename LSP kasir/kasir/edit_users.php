<?php
session_start();
include "config.php";

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak! Hanya admin yang dapat mengakses halaman ini.');window.location.href='login.php';</script>";
    exit();
}

// Ambil ID pengguna dari parameter URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID pengguna tidak ditemukan!');window.location.href='users.php';</script>";
    exit();
}

$id = $_GET['id'];

// Ambil data pengguna berdasarkan ID
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($result);

// Jika pengguna tidak ditemukan
if (!$user) {
    echo "<script>alert('Pengguna tidak ditemukan!');window.location.href='users.php';</script>";
    exit();
}

// Update data pengguna
if (isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Cek apakah pengguna yang diedit adalah admin
    if ($user['role'] === 'admin' && $role !== 'admin') {
        echo "<script>alert('Anda tidak dapat mengubah admin menjadi peran lain!');window.location.href='edit_user.php?id=$id';</script>";
        exit();
    }

    // Update data pengguna di database
    $query = "UPDATE users SET username='$username', role='$role' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pengguna berhasil diperbarui!');window.location.href='users.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui pengguna!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Pengguna</h2>

        <!-- Tombol Kembali ke Manajemen Pengguna -->
        <a href="users.php" class="btn btn-secondary mb-3">⬅ Kembali ke Manajemen Pengguna</a>

        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= $user['username'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="petugas" <?= $user['role'] === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                </select>
            </div>
            <button type="submit" name="update_user" class="btn btn-success">✅ Simpan Perubahan</button>
        </form>
    </div>
</body>

</html>