<?php
include 'config.php';

if (isset($_GET['id'])) {
    $penjualan_id = $_GET['id'];

    // Ambil data penjualan
    $penjualan = $conn->query("SELECT * FROM penjualan WHERE penjualan_id = $penjualan_id")->fetch_assoc();

    // Ambil detail penjualan
    $detail = $conn->query("SELECT * FROM detail_penjualan WHERE penjualan_id = $penjualan_id")->fetch_assoc();

    // Ambil daftar pelanggan dan produk
    $pelanggan = $conn->query("SELECT * FROM pelanggan");
    $produk = $conn->query("SELECT * FROM produk");
}

if (isset($_POST['update_penjualan'])) {
    $tanggal = $_POST['tanggal_penjualan'];
    $pelanggan_id = $_POST['pelanggan_id'];
    $produk_id = $_POST['produk_id'];
    $jumlah_baru = $_POST['jumlah_produk'];

    // Ambil data produk lama & baru
    $produk_lama = $conn->query("SELECT * FROM produk WHERE produk_id = {$detail['produk_id']}")->fetch_assoc();
    $produk_baru = $conn->query("SELECT * FROM produk WHERE produk_id = $produk_id")->fetch_assoc();

    // Hitung subtotal
    $subtotal_baru = $produk_baru['harga'] * $jumlah_baru;

    // Update stok: kembalikan stok lama, kurangi stok baru
    $conn->query("UPDATE produk SET stok = stok + {$detail['jumlah_produk']} WHERE produk_id = {$detail['produk_id']}");
    $conn->query("UPDATE produk SET stok = stok - $jumlah_baru WHERE produk_id = $produk_id");

    // Update data penjualan
    $conn->query("UPDATE penjualan SET 
        tanggal_penjualan = '$tanggal',
        total_harga = '$subtotal_baru',
        pelanggan_id = '$pelanggan_id'
        WHERE penjualan_id = $penjualan_id");

    // Update detail penjualan
    $conn->query("UPDATE detail_penjualan SET 
        produk_id = '$produk_id', 
        jumlah_produk = '$jumlah_baru', 
        sub_total = '$subtotal_baru' 
        WHERE penjualan_id = $penjualan_id");

    echo "<script>alert('Penjualan berhasil diperbarui!');window.location.href='penjualan.php';</script>";
}
?>

<h2>Edit Penjualan</h2>
<form method="post">
    Tanggal: <input type="date" name="tanggal_penjualan" value="<?= $penjualan['tanggal_penjualan'] ?>" required>
    <br>
    Pelanggan:
    <select name="pelanggan_id" required>
        <?php while ($row = $pelanggan->fetch_assoc()) { ?>
            <option value="<?= $row['pelanggan_id'] ?>" <?= ($row['pelanggan_id'] == $penjualan['pelanggan_id']) ? 'selected' : '' ?>>
                <?= $row['nama_pelanggan'] ?>
            </option>
        <?php } ?>
    </select>
    <br>
    Produk:
    <select name="produk_id" required>
        <?php while ($row = $produk->fetch_assoc()) { ?>
            <option value="<?= $row['produk_id'] ?>" <?= ($row['produk_id'] == $detail['produk_id']) ? 'selected' : '' ?>>
                <?= $row['nama_produk'] ?> - Stok: <?= $row['stok'] ?>
            </option>
        <?php } ?>
    </select>
    <br>
    Jumlah: <input type="number" name="jumlah_produk" value="<?= $detail['jumlah_produk'] ?>" min="1" required>
    <br>
    <button type="submit" name="update_penjualan">Simpan Perubahan</button>
</form>