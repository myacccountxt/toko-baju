<?php

include 'koneksi.php';
include 'inc/header.php';
?>

<div class="hero-section">
    <div class="hero-content">
        <h2>Temukan Gaya Terbaru Anda</h2>
        <p>Koleksi pakaian terbaik dengan desain eksklusif dan kualitas premium.</p>
        <a href="produk.php" class="cta-button">Lihat Semua Koleksi</a>
    </div>
</div>

<div class="container featured-products">
    <h3>Produk Unggulan Kami</h3>

    <?php

    $query = "SELECT * FROM produk WHERE status_verifikasi = 'APPROVED' ORDER BY id_produk DESC LIMIT 6";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0): ?>
        <div class="product-grid">
            <?php while($row = $result->fetch_assoc()):
            // Pastikan path gambar yang digunakan: assets/images/
            $image_path = 'assets/images/' . htmlspecialchars($row['gambar']);
            $product_name = htmlspecialchars($row['nama_produk']);
            $product_stock = htmlspecialchars($row['stok']);
            $product_category = htmlspecialchars($row['kategori']);
            $product_price = number_format($row['harga'], 0, ',', '.');
            $product_id = $row['id_produk'];
            ?>
            <div class="product-card">
                <div class="product-image-container">
                    <img
                        src="<?php echo $image_path; ?>"
                        alt="<?php echo $product_name; ?>"
                        class="product-image"
                    >
                    <span class="stock-badge">
                        Stok: <?php echo $product_stock; ?>
                    </span>
                </div>
                <div class="product-info">
                    <h3 class="product-title">
                        <?php echo $product_name; ?>
                    </h3>
                    <p class="product-category">
                        Kategori: <?php echo $product_category; ?>
                    </p>
                    <p class="product-price">
                        Rp <?php echo $product_price; ?>
                    </p>
                    <a href="detail_produk.php?id=<?php echo $product_id; ?>" class="add-to-cart-btn">
                        Lihat Detail
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Belum ada produk unggulan yang disetujui untuk ditampilkan saat ini.</p>
    <?php endif; ?>
</div>

<?php
include 'inc/footer.php';

// Tutup koneksi database
$koneksi->close();
?>