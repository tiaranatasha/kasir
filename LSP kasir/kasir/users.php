<?php
session_start();
include "config.php";

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak! Hanya admin yang dapat mengakses halaman ini.');window.location.href='login.php';</script>";
    exit();
}

// Tambah Pengguna
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pengguna berhasil ditambahkan!'); window.location.href='users.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan pengguna!');</script>";
    }
}

// Hapus Pengguna
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $result = mysqli_query($conn, "SELECT role FROM users WHERE id=$id");
    $user = mysqli_fetch_assoc($result);

    if ($user['role'] == 'admin') {
        echo "<script>alert('Admin tidak dapat dihapus!'); window.location.href='users.php';</script>";
    } else {
        mysqli_query($conn, "DELETE FROM users WHERE id=$id");
        echo "<script>alert('Pengguna berhasil dihapus!'); window.location.href='users.php';</script>";
    }
}

$users = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            background-color: #212529;
            padding-top: 20px;
            color: white;
        }

        .sidebar a {
            padding: 12px;
            text-decoration: none;
            color: white;
            display: block;
            font-weight: bold;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #495057;
            border-radius: 5px;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <a href="produk.php">📦 Produk</a>
        <a href="pelanggan.php">👥 Pelanggan</a>
        <a href="penjualan.php">📋 Penjualan</a>
        <a href="users.php" class="bg-primary">👤 Users</a>
        <a href="logout.php" class="logout">🚪 Logout</a>
    </div>

    <div class="content">
        <div class="container mt-5">
            <h2 class="mb-4">Manajemen Pengguna</h2>
            <div class="table-container">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($users)) { ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td><?= ucfirst($row['role']) ?></td>
                                <td>
                                    <a href="edit_users.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏ Edit</a>
                                    <?php if ($row['role'] !== 'admin') { ?>
                                        <a href="users.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pengguna ini?')">🗑 Hapus</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <h3 class="mt-4">Tambah Pengguna</h3>
            <div class="table-container">
                <form method="POST">
                    <div class="mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="mb-3">
                        <select name="role" class="form-control" required>
                            <option value="select">Select</option>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                    <button type="submit" name="add_user" class="btn btn-success">➕ Tambah Pengguna</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>