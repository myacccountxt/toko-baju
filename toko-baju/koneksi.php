<?php
// Konfigurasi Database
$host     = 'localhost'; // Biasanya localhost
$user     = 'root';      // Ganti dengan username database Anda
$password = '';          // Ganti dengan password database Anda
$database = 'toko_baju_db'; // Nama database yang sudah Anda buat

// Membuat koneksi
$koneksi = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Set karakter set ke utf8 untuk mendukung berbagai karakter
$koneksi->set_charset("utf8");
// echo "Koneksi berhasil!"; // Hapus baris ini setelah pengujian
?>