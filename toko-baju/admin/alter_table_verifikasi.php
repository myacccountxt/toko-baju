<?php
include '../koneksi.php';

// Query untuk menambahkan kolom status_verifikasi jika belum ada
$sql = "ALTER TABLE produk ADD COLUMN status_verifikasi ENUM('pending', 'verified', 'rejected') DEFAULT 'pending'";

if ($koneksi->query($sql) === TRUE) {
    echo "Kolom status_verifikasi berhasil ditambahkan ke tabel produk.";
} else {
    echo "Error: " . $koneksi->error;
}

$koneksi->close();
?>
