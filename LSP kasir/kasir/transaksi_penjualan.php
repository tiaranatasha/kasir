<?php
session_start();
include "config.php";

// Pastikan ada ID penjualan yang dikirim
if (!isset($_GET['penjualan_id'])) {
    echo "ID Penjualan tidak ditemukan.";
    exit();
}

$penjualan_id = $_GET['penjualan_id'];

// Ambil data penjualan
$penjualan_query = mysqli_query($conn, "SELECT p.*, pl.nama AS pelanggan 
                                        FROM penjualan p 
                                        JOIN pelanggan pl ON p.pelanggan_id = pl.id 
                                        WHERE p.id = '$penjualan_id'");

$penjualan = mysqli_fetch_assoc($penjualan_query);
if (!$penjualan) {
    echo "Data penjualan tidak ditemukan.";
    exit();
}

// Ambil detail penjualan
$detail_query = mysqli_query($conn, "SELECT dp.*, pr.nama AS produk, pr.harga 
                                     FROM detail_penjualan dp 
                                     JOIN produk pr ON dp.produk_id = pr.id 
                                     WHERE dp.penjualan_id = '$penjualan_id'");
?>

<h2>Detail Transaksi Penjualan</h2>

<p><strong>ID Penjualan:</strong> <?= $penjualan['id'] ?></p>
<p><strong>Pelanggan:</strong> <?= $penjualan['pelanggan'] ?></p>
<p><strong>Total Harga:</strong> Rp<?= number_format($penjualan['total_harga'], 2) ?></p>
<p><strong>Tanggal:</strong> <?= $penjualan['tanggal'] ?></p>

<h3>Daftar Produk</h3>
<table border="1">
    <tr>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($detail_query)) { ?>
        <tr>
            <td><?= $row['produk'] ?></td>
            <td>Rp<?= number_format($row['harga'], 2) ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td>Rp<?= number_format($row['sub_total'], 2) ?></td>
        </tr>
    <?php } ?>
</table>

<a href="penjualan.php">Kembali</a>