<?php
// Di lingkungan produksi, Anda harus menambahkan sistem otentikasi
include '../koneksi.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_produk = (int)$_GET['id'];

    // Sebelum menghapus record, biasanya perlu menghapus file gambar terkait
    // (Logika ini diabaikan untuk kesederhanaan, tetapi penting untuk produksi)

    $sql = "DELETE FROM produk WHERE id_produk = $id_produk";

    if ($koneksi->query($sql) === TRUE) {
        $status = "Produk ID $id_produk berhasil dihapus.";
    } else {
        $status = "Error saat menghapus produk: " . $koneksi->error;
    }
} else {
    $status = "ID Produk tidak valid atau tidak ditemukan.";
}

$koneksi->close();

// Redirect kembali ke dashboard admin setelah operasi selesai
header("Location: dashboard.php?status=" . urlencode($status));
exit();
?>