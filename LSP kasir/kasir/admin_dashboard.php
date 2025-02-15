<?php
session_start();
include "config.php";

// Cek jika user bukan admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .welcome-container {
      text-align: center;
      margin-bottom: 20px;
    }

    /* Sidebar Navigasi */
    .sidebar {
      height: 100vh;
      width: 250px;
      position: fixed;
      left: 0;
      top: 0;
      background-color: #212529;
      padding-top: 20px;
      display: none;
      /* Sidebar akan muncul setelah klik tombol */
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

    .sidebar .active {
      background-color: #0d6efd;
      border-radius: 5px;
    }

    .logout {
      position: absolute;
      bottom: 20px;
      width: 100%;
    }

    /* Konten Dashboard */
    .content {
      margin-left: 270px;
      padding: 20px;
      display: none;
      /* Muncul setelah sidebar aktif */
    }
  </style>
</head>

<body>
  <!-- Selamat Datang -->
  <div class="welcome-container">
    <h1 class="text-primary">Selamat Datang, Admin!</h1>
    <p>Klik tombol di bawah ini untuk masuk ke Dashboard.</p>
    <button id="enterDashboard" class="btn btn-success">Masuk ke Dashboard</button>
  </div>

  <!-- Sidebar Navigasi -->
  <div class="sidebar" id="sidebar">
    <a href="produk.php">📦 Produk</a>
    <a href="pelanggan.php">👥 Pelanggan</a>
    <a href="penjualan.php">📋 Penjualan</a>
    <a href="users.php">👤 Users</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <!-- Konten Dashboard
  <div class="content" id="content">
    <h2 class="text-center text-primary">🏠 Dashboard Admin</h2>
    <p class="text-center">Selamat datang, <b><?= $_SESSION['username']; ?></b>! Gunakan menu di sebelah kiri untuk navigasi.</p>
  </div> -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById("enterDashboard").addEventListener("click", function() {
      document.querySelector(".welcome-container").style.display = "none";
      document.getElementById("sidebar").style.display = "block";
      // document.getElementById("content").style.display = "block";
    });
  </script>
</body>

</html>