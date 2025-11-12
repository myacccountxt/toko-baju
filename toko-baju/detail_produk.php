<?php
include 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: produk.php');
    exit();
}

$id_produk = (int)$_GET['id'];
$query = "SELECT * FROM produk WHERE id_produk = $id_produk AND status_verifikasi = 'verified'";
$result = $koneksi->query($query);

if ($result->num_rows === 0) {
    echo "<p>Produk tidak ditemukan!</p>";
    include 'inc/footer.php';
    $koneksi->close();
    exit();
}

$produk = $result->fetch_assoc();

include 'inc/header.php';
?>

<div class="container detail-container">
    <div class="detail-grid">
        <div class="detail-image">
            <img
                src="assets/images/<?php echo htmlspecialchars($produk['gambar']); ?>"
                alt="<?php echo htmlspecialchars($produk['nama_produk']); ?>"
            >
        </div>
        <div class="detail-info">
            <h1><?php echo htmlspecialchars($produk['nama_produk']); ?></h1>
            <p class="detail-price">
                Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?>
            </p>
            <p class="detail-stock">Stok Tersedia: <strong><?php echo $produk['stok']; ?></strong></p>

            <div class="detail-description">
                <h3>Deskripsi</h3>
                <p><?php echo nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>
            </div>

            <div class="detail-actions">
                <button class="add-to-cart-btn large-btn">
                    Beli Sekarang (Fitur Keranjang)
                </button>
                <p class="detail-category">Kategori: <span><?php echo htmlspecialchars($produk['kategori']); ?></span></p>
            </div>
        </div>
    </div>
</div>

<?php
include 'inc/footer.php';
$koneksi->close();
?>