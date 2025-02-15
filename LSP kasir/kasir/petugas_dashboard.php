<?php
session_start();
include "config.php";

// Cek jika user bukan petugas
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'petugas') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .welcome-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #343a40;
            padding-top: 20px;
            display: none;
            /* Sidebar akan muncul setelah klik tombol */
        }

        .sidebar a {
            padding: 10px;
            text-decoration: none;
            color: white;
            display: block;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .sidebar .active {
            background-color: #0d6efd;
        }

        .logout {
            position: absolute;
            bottom: 20px;
            width: 100%;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
            display: none;
            /* Konten muncul setelah sidebar aktif */
        }
    </style>
</head>

<body>
    <!-- Selamat Datang -->
    <div class="welcome-container">
        <h1 class="text-primary">Selamat Datang, Petugas!</h1>
        <p>Klik tombol di bawah ini untuk masuk ke Dashboard.</p>
        <button id="enterDashboard" class="btn btn-success">Masuk ke Dashboard</button>
    </div>

    <!-- Sidebar Navigasi -->
    <div class="sidebar" id="sidebar">
        <a href="lihat_produk.php">📦 Lihat Produk</a>
        <a href="lihat_pelanggan.php">👥 Lihat Pelanggan</a>
        <a href="lihat_penjualan.php">📋 Lihat Penjualan</a>
        <a href="lihat_detail_penjualan.php">🛒 Lihat Detail Penjualan</a>
        <a href="logout.php">🚪 Logout</a>
    </div>


    <!-- Konten Dashboard -->
    <div class="content">
        <h2 class="text-center text-primary">🏠 Dashboard Petugas</h2>
        <p class="text-center">Selamat datang, <b><?= $_SESSION['username']; ?></b>! Gunakan menu di sebelah kiri untuk navigasi.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("enterDashboard").addEventListener("click", function() {
            document.querySelector(".welcome-container").style.display = "none";
            document.getElementById("sidebar").style.display = "block";
            document.getElementById("content").style.display = "block";
        });
    </script>
</body>

</html>