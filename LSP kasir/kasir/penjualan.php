<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$produk = $conn->query("SELECT * FROM produk WHERE stok > 0");
$pelanggan = $conn->query("SELECT * FROM pelanggan");

if (isset($_POST['tambah_penjualan'])) {
    $tanggal = $_POST['tanggal_penjualan'];
    $pelanggan_id = $_POST['pelanggan_id'];
    $produk_id = $_POST['produk_id'];
    $jumlah = $_POST['jumlah_produk'];

    $produk_data = $conn->query("SELECT * FROM produk WHERE produk_id = $produk_id")->fetch_assoc();

    if ($produk_data['stok'] >= $jumlah) {
        $total_harga = $produk_data['harga'] * $jumlah;
        $conn->query("INSERT INTO penjualan (tanggal_penjualan, total_harga, pelanggan_id) VALUES ('$tanggal', '$total_harga', '$pelanggan_id')");
        $penjualan_id = $conn->insert_id;
        $conn->query("INSERT INTO detail_penjualan (penjualan_id, produk_id, jumlah_produk, sub_total) VALUES ('$penjualan_id', '$produk_id', '$jumlah', '$total_harga')");
        $conn->query("UPDATE produk SET stok = stok - $jumlah WHERE produk_id = $produk_id");
        echo "<script>alert('Penjualan berhasil!');window.location.href='penjualan.php';</script>";
    } else {
        echo "<script>alert('Stok tidak cukup!');</script>";
    }
}

$penjualan = $conn->query("SELECT p.penjualan_id, p.tanggal_penjualan, pl.nama_pelanggan, d.jumlah_produk, pr.nama_produk, p.total_harga FROM penjualan p JOIN pelanggan pl ON p.pelanggan_id = pl.pelanggan_id JOIN detail_penjualan d ON p.penjualan_id = d.penjualan_id JOIN produk pr ON d.produk_id = pr.produk_id");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penjualan</title>
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
        <a href="pelanggan.php">👥 Pelanggan</a>
        <a href="penjualan.php" class="bg-primary">📋 Penjualan</a>
        <a href="users.php">👤 Users</a>
        <a href="logout.php" class="logout">🚪 Logout</a>
    </div>
    <div class="content">
        <h2 class="mb-4 text-center">Data Penjualan</h2>
        <a href="cetak_data.php" class="btn btn-primary mb-3">🖨️ Cetak Semua Data</a>
        <div class="card p-3 mb-4">
            <form method="post">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Tanggal:</label>
                        <input type="date" name="tanggal_penjualan" class="form-control" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Pelanggan:</label>
                        <select name="pelanggan_id" class="form-control" required>
                            <option value="">Pilih</option>
                            <?php while ($row = $pelanggan->fetch_assoc()) { ?>
                                <option value="<?= $row['pelanggan_id'] ?>"><?= $row['nama_pelanggan'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Produk:</label>
                        <select name="produk_id" class="form-control" required>
                            <option value="">Pilih</option>
                            <?php while ($row = $produk->fetch_assoc()) { ?>
                                <option value="<?= $row['produk_id'] ?>">
                                    <?= $row['nama_produk'] ?> - Stok: <?= $row['stok'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Jumlah:</label>
                        <input type="number" name="jumlah_produk" class="form-control" min="1" required>
                    </div>
                </div>
                <button type="submit" name="tambah_penjualan" class="btn btn-success">Tambah</button>
            </form>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $penjualan->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['penjualan_id'] ?></td>
                        <td><?= $row['tanggal_penjualan'] ?></td>
                        <td><?= $row['nama_pelanggan'] ?></td>
                        <td><?= $row['nama_produk'] ?></td>
                        <td><?= $row['jumlah_produk'] ?></td>
                        <td><?= number_format($row['total_harga'], 0, ',', '.') ?></td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>