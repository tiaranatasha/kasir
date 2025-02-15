<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM produk WHERE produk_id = $id");
    echo "<script>alert('Produk berhasil dihapus!');window.location.href='produk.php';</script>";
}
