<?php
// Di file index.php atau file lain yang menggunakan header ini, pastikan baris koneksi.php sudah dipanggil sebelum include header!
// Contoh: include 'koneksi.php';
// Jika file ini diakses langsung, variabel $_GET mungkin tidak terdefinisi, tetapi di lingkungan PHP/web seharusnya aman.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion Store | Toko Baju Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<header class="main-header">
    <div class="container header-inner">
        <div class="logo">
            <a href="index.php"><h1>FASHION BOUTIQUE</h1></a>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="produk.php">Produk</a></li>
                <li><a href="tentang_kami.php">Tentang Kami</a></li>
                <li><a href="admin/dashboard.php">Admin</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="search-container">
    <div class="container search-wrapper">
        <form action="produk.php" method="GET" class="search-form">
            <input
                type="text"
                name="keyword"
                placeholder="Cari baju, kategori, atau warna..."
                class="search-input"
                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>"
            >
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>

<main>