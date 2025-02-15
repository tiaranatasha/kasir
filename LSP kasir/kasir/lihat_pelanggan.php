<?php
session_start();
include 'config.php';

// Periksa apakah pengguna adalah petugas
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
    echo "<script>alert('Akses ditolak! Halaman ini hanya untuk petugas.');window.location.href='login.php';</script>";
    exit();
}

// Ambil Data Pelanggan
$pelanggan = $conn->query("SELECT * FROM pelanggan");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            color: white;
            font-size: 16px;
            transition: 0.3s;
            display: flex;
            align-items: center;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: #0d6efd;
            color: white;
            border-left: 5px solid #fff;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .logout {
            margin-top: auto;
            padding: 15px;
            text-align: center;
            background-color: #dc3545;
            font-weight: bold;
        }

        .logout:hover {
            background-color: #c82333;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
            width: 100%;
        }

        .container {
            max-width: 900px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
            color: #0d6efd;
        }

        .table th {
            background-color: #0d6efd;
            color: white;
            text-align: center;
        }

        .table td {
            text-align: center;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
            transition: 0.3s;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <a href="lihat_produk.php">📦 Lihat Produk</a>
        <a href="lihat_pelanggan.php" class="active">👥 Lihat Pelanggan</a>
        <a href="lihat_penjualan.php">📋 Lihat Penjualan</a>
        <a href="lihat_detail_penjualan.php">🛒 Lihat Detail Penjualan</a>
        <a href="logout.php" class="logout">🚪 Logout</a>
    </div>

    <div class="content">
        <div class="container">
            <h2>Data Pelanggan</h2>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No Telepon</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $pelanggan->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['pelanggan_id']) ?></td>
                            <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                            <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>