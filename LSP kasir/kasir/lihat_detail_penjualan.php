<?php
session_start();
include 'config.php';

// Periksa apakah pengguna adalah petugas
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
    echo "<script>alert('Akses ditolak! Halaman ini hanya untuk petugas.');window.location.href='login.php';</script>";
    exit();
}

// Ambil Data Detail Penjualan
$detail_penjualan = $conn->query("SELECT dp.detail_id, p.tanggal_penjualan, pl.nama_pelanggan, pr.nama_produk, dp.jumlah_produk, dp.sub_total 
                                   FROM detail_penjualan dp
                                   JOIN penjualan p ON dp.penjualan_id = p.penjualan_id
                                   JOIN pelanggan pl ON p.pelanggan_id = pl.pelanggan_id
                                   JOIN produk pr ON dp.produk_id = pr.produk_id");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            display: flex;
        }

        /* Sidebar Styling */
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

        /* Content Styling */
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

        /* Table Styling */
        .table {
            border-radius: 10px;
            overflow: hidden;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 210px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100px;
                text-align: center;
            }

            .sidebar a {
                font-size: 12px;
                padding: 10px;
            }

            .sidebar a i {
                display: block;
                margin: auto;
            }

            .content {
                margin-left: 110px;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <a href="lihat_produk.php"><i>📦</i> Lihat Produk</a>
        <a href="lihat_pelanggan.php"><i>👥</i> Lihat Pelanggan</a>
        <a href="lihat_penjualan.php"><i>📋</i> Lihat Penjualan</a>
        <a href="lihat_detail_penjualan.php" class="active"><i>🛒</i> Detail Penjualan</a>
        <a href="logout.php" class="logout"><i>🚪</i> Logout</a>
    </div>

    <div class="content">
        <div class="container">
            <h2>Detail Penjualan</h2>
            <a href="cetak_data.php" class="btn btn-primary mb-3">🖨️ Cetak Semua Data</a>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID Detail</th>
                        <th>Tanggal Penjualan</th>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $detail_penjualan->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['detail_id'] ?></td>
                            <td><?= date("d-m-Y", strtotime($row['tanggal_penjualan'])) ?></td>
                            <td><?= $row['nama_pelanggan'] ?></td>
                            <td><?= $row['nama_produk'] ?></td>
                            <td><?= $row['jumlah_produk'] ?></td>
                            <td>Rp <?= number_format($row['sub_total'], 0, ',', '.') ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>