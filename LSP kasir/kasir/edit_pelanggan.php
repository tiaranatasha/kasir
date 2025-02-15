<?php
session_start();
require 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$editMode = false;
$pelanggan = null;

// Periksa apakah ada ID pelanggan yang dikirim untuk diedit
if (isset($_GET['id'])) {
    $editMode = true;
    $pelanggan_id = $_GET['id'];

    // Ambil data pelanggan dari database
    $stmt = $conn->prepare("SELECT * FROM pelanggan WHERE pelanggan_id = ?");
    $stmt->bind_param("i", $pelanggan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pelanggan = $result->fetch_assoc();
    } else {
        echo "Pelanggan tidak ditemukan.";
        exit();
    }
}

// Proses update data pelanggan
if (isset($_POST['update'])) {
    $pelanggan_id = $_POST['pelanggan_id'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];

    // Update data pelanggan di database
    $stmt = $conn->prepare("UPDATE pelanggan SET nama_pelanggan = ?, alamat = ?, no_telepon = ? WHERE pelanggan_id = ?");
    $stmt->bind_param("sssi", $nama_pelanggan, $alamat, $no_telepon, $pelanggan_id);
    $stmt->execute();

    header("Location: pelanggan.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Edit Pelanggan</h2>

        <form method="post">
            <input type="hidden" name="pelanggan_id" value="<?= $pelanggan ? $pelanggan['pelanggan_id'] : ''; ?>">

            <div class="mb-3">
                <label class="form-label">Nama Pelanggan:</label>
                <input type="text" name="nama_pelanggan" class="form-control" value="<?= $pelanggan ? htmlspecialchars($pelanggan['nama_pelanggan']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat:</label>
                <textarea name="alamat" class="form-control" required><?= $pelanggan ? htmlspecialchars($pelanggan['alamat']) : ''; ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">No Telepon:</label>
                <input type="text" name="no_telepon" class="form-control" value="<?= $pelanggan ? $pelanggan['no_telepon'] : ''; ?>" required>
            </div>

            <button type="submit" name="update" class="btn btn-warning">Update</button>
            <a href="pelanggan.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>