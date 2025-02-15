<?php
include 'config.php';

// Ambil Data Detail Penjualan
$detail_penjualan = $conn->query("SELECT dp.detail_id, p.tanggal_penjualan, pl.nama_pelanggan, pr.nama_produk, dp.jumlah_produk, pr.harga, dp.sub_total 
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
    <title>Cetak Data Detail Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f8f9fa;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .cetak {
            margin-top: 20px;
            text-align: center;
        }

        .cetak button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .cetak button:hover {
            background-color: #218838;
        }

        @media print {
            .cetak {
                display: none;
            }
        }
    </style>
</head>

<body>

    <h2>Detail Penjualan</h2>
    <table>
        <tr>
            <th>ID Detail</th>
            <th>Tanggal Penjualan</th>
            <th>Pelanggan</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Sub Total</th>
        </tr>
        <?php while ($row = $detail_penjualan->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['detail_id'] ?></td>
                <td><?= $row['tanggal_penjualan'] ?></td>
                <td><?= $row['nama_pelanggan'] ?></td>
                <td><?= $row['nama_produk'] ?></td>
                <td><?= $row['jumlah_produk'] ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($row['sub_total'], 0, ',', '.') ?></td>
            </tr>
        <?php } ?>
    </table>

    <!-- Tombol Cetak -->
    <div class="cetak">
        <button onclick="window.print()">🖨️ Cetak</button>
        <!-- <a href="admin_dashboard.php" class="btn btn-primary mb-3">kembali</a>
        <a href="petugas_dashboard.php" class="btn btn-primary mb-3">kembali</a> -->
    </div>

</body>

</html>