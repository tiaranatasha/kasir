<?php
session_start();
include 'config.php';

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Tambah Pelanggan (Menggunakan Prepared Statements)
if (isset($_POST['tambah_pelanggan'])) {
    $nama = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];

    $stmt = $conn->prepare("INSERT INTO pelanggan (nama_pelanggan, alamat, no_telepon) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $alamat, $no_telepon);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Pelanggan berhasil ditambahkan!');window.location.href='pelanggan.php';</script>";
}

// Hapus Pelanggan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM pelanggan WHERE pelanggan_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Pelanggan berhasil dihapus!');window.location.href='pelanggan.php';</script>";
}

// Ambil Data Pelanggan
$pelanggan = $conn->query("SELECT * FROM pelanggan");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan</title>
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
    </style>
</head>

<body>
    <div class="sidebar">
        <a href="produk.php">📦 Produk</a>
        <a href="pelanggan.php" class="bg-primary">👥 Pelanggan</a>
        <a href="penjualan.php">📋 Penjualan</a>
        <a href="users.php">👤 Users</a>
        <a href="logout.php" class="logout">🚪 Logout</a>
    </div>

    <div class="content">
        <div class="container mt-4">
            <h2 class="mb-4 text-center">Data Pelanggan</h2>

            <!-- Tombol Tambah Pelanggan -->
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahPelanggan">
                ➕ Tambah Pelanggan
            </button>

            <!-- Modal Tambah Pelanggan -->
            <div class="modal fade" id="modalTambahPelanggan" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel">Tambah Pelanggan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="nama_pelanggan" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No Telepon</label>
                                    <input type="text" name="no_telepon" class="form-control" required>
                                </div>
                                <button type="submit" name="tambah_pelanggan" class="btn btn-primary w-100">Tambah</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4 p-4 shadow-sm">
                <h4 class="text-center">Daftar Pelanggan</h4>
                <table class="table table-bordered mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $pelanggan->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $row['pelanggan_id'] ?></td>
                                <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                                <td>
                                    <a href="edit_pelanggan.php?id=<?= $row['pelanggan_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="pelanggan.php?hapus=<?= $row['pelanggan_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>