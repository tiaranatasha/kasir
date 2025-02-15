<?php
session_start();
require 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$editMode = false;
$produk = null;

// Periksa apakah ada ID produk yang dikirim untuk diedit
if (isset($_GET['id'])) {
    $editMode = true;
    $produk_id = $_GET['id'];

    // Ambil data produk dari database
    $stmt = $conn->prepare("SELECT * FROM produk WHERE produk_id = ?");
    $stmt->bind_param("i", $produk_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $produk = $result->fetch_assoc();
    } else {
        echo "Produk tidak ditemukan.";
        exit();
    }

    // Ambil total produk yang telah terjual dari tabel detail_penjualan
    $stmt = $conn->prepare("SELECT COALESCE(SUM(jumlah_produk), 0) AS total_terjual FROM detail_penjualan WHERE produk_id = ?");
    $stmt->bind_param("i", $produk_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_terjual = $row['total_terjual'];
}

// Proses update data produk
if (isset($_POST['update'])) {
    $produk_id = $_POST['produk_id'];
    $nama_produk = $_POST['nama_produk'];
    $stok_baru = $_POST['stok'];
    $harga = $_POST['harga'];

    // Hitung stok akhir setelah mempertahankan jumlah yang telah terjual
    $stok_akhir = max(0, $stok_baru - $total_terjual);

    // Update data produk di database
    $stmt = $conn->prepare("UPDATE produk SET nama_produk = ?, harga = ?, stok = ? WHERE produk_id = ?");
    $stmt->bind_param("sdii", $nama_produk, $harga, $stok_akhir, $produk_id);
    $stmt->execute();

    header("Location: produk.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Edit Produk</h2>

        <form method="post">
            <input type="hidden" name="produk_id" value="<?= $produk ? $produk['produk_id'] : ''; ?>">

            <div class="mb-3">
                <label class="form-label">Nama Produk:</label>
                <input type="text" name="nama_produk" class="form-control" value="<?= $produk ? htmlspecialchars($produk['nama_produk']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga:</label>
                <input type="number" step="0.01" name="harga" class="form-control" value="<?= $produk ? $produk['harga'] : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stok:</label>
                <input type="number" name="stok" class="form-control" value="<?= $produk ? $produk['stok'] + $total_terjual : ''; ?>" required>
                <small class="text-muted">Produk terjual: <?= $total_terjual; ?></small>
            </div>

            <button type="submit" name="update" class="btn btn-warning">Update</button>
            <a href="produk.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>