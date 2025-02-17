<?php
include 'config.php';

// Periksa apakah pengguna adalah admin
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penjualan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: blue;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .btn:hover {
            background-color: darkblue;
        }
    </style>
</head>

<body>
    <h2>Detail Penjualan</h2>
    
    <a href="cetak_data.php" class="btn">🖨️ Cetak Semua Data</a>
    
    <table>
        <tr>
            <th>ID Detail</th>
            <th>Tanggal Penjualan</th>
            <th>Pelanggan</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Sub Total</th>
        </tr>
        <?php while ($row = $detail_penjualan->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['detail_id'] ?></td>
                <td><?= $row['tanggal_penjualan'] ?></td>
                <td><?= $row['nama_pelanggan'] ?></td>
                <td><?= $row['nama_produk'] ?></td>
                <td><?= $row['jumlah_produk'] ?></td>
                <td><?= number_format($row['sub_total'], 0, ',', '.') ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
