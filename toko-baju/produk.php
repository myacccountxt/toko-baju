<?php
include 'koneksi.php';
include 'inc/header.php';

// Logika Pencarian
$keyword = '';
$where_clause = '';

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = $koneksi->real_escape_string($_GET['keyword']);
    // Mencari di nama produk atau deskripsi
    $where_clause = " WHERE nama_produk LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%' OR kategori LIKE '%$keyword%'";
}

// Query untuk mengambil data produk yang sudah diverifikasi
$query = "SELECT * FROM produk WHERE status_verifikasi = 'verified'" . $where_clause . " ORDER BY id_produk DESC";
$result = $koneksi->query($query);
?>

<div class="container">
    <h2>Semua Produk</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="product-grid">
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <div class="product-image-container">
                    <img
                        src="assets/images/<?php echo htmlspecialchars($row['gambar']); ?>"
                        alt="<?php echo htmlspecialchars($row['nama_produk']); ?>"
                        class="product-image"
                    >
                    <span class="stock-badge">
                        Stok: <?php echo htmlspecialchars($row['stok']); ?>
                    </span>
                </div>
                <div class="product-info">
                    <h3 class="product-title">
                        <?php echo htmlspecialchars($row['nama_produk']); ?>
                    </h3>
                    <p class="product-category">
                        <?php echo htmlspecialchars($row['kategori']); ?>
                    </p>
                    <p class="product-price">
                        Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                    </p>
                    <a href="detail_produk.php?id=<?php echo $row['id_produk']; ?>" class="add-to-cart-btn">
                        Lihat Detail
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="no-result">
            <?php echo !empty($keyword) ?
                'Maaf, produk dengan kata kunci "'. htmlspecialchars($keyword) .'" tidak ditemukan.' :
                'Belum ada produk yang tersedia saat ini.'; ?>
        </p>
    <?php endif; ?>
</div>

<?php
include 'inc/footer.php';
$koneksi->close();
?>