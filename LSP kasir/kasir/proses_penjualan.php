<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $pelanggan_id = $_POST['pelanggan_id'];
    $produk_id = $_POST['produk_id'];
    $jumlah = $_POST['jumlah'];

    // Ambil harga produk
    $produk = mysqli_query($conn, "SELECT * FROM produk WHERE id = '$produk_id'");
    $produk_data = mysqli_fetch_assoc($produk);
    $sub_total = $produk_data['harga'] * $jumlah;

    // Simpan transaksi ke tabel `penjualan`
    mysqli_query($conn, "INSERT INTO penjualan (user_id, pelanggan_id, total_harga) 
                         VALUES ('$user_id', '$pelanggan_id', '$sub_total')");

    // Ambil ID transaksi terbaru
    $penjualan_id = mysqli_insert_id($conn);

    // Simpan detail ke tabel `detail_penjualan`
    mysqli_query($conn, "INSERT INTO detail_penjualan (penjualan_id, produk_id, jumlah, sub_total) 
                         VALUES ('$penjualan_id', '$produk_id', '$jumlah', '$sub_total')");

    // Kurangi stok produk
    mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah WHERE id = '$produk_id'");

    header("Location: detail_penjualan.php?penjualan_id=$penjualan_id");
}
