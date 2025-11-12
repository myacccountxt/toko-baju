<?php
include '../koneksi.php';

// Proses verifikasi produk
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_produk']) && isset($_POST['status'])) {
    $id_produk = (int)$_POST['id_produk'];
    $status = $koneksi->real_escape_string($_POST['status']);

    // Validasi status
    if (!in_array($status, ['verified', 'rejected'])) {
        $message = "Status verifikasi tidak valid.";
    } else {
        $sql = "UPDATE produk SET status_verifikasi = '$status' WHERE id_produk = $id_produk";

        if ($koneksi->query($sql) === TRUE) {
            $message = "Produk berhasil " . ($status == 'verified' ? 'diverifikasi' : 'ditolak') . ".";
        } else {
            $message = "Error: " . $koneksi->error;
        }
    }
}

// Query untuk mengambil produk yang perlu diverifikasi
$query = "SELECT * FROM produk WHERE status_verifikasi = 'pending' ORDER BY id_produk DESC";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Produk - Admin</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <script src="../assets/js/script.js"></script>
    <style>
        .verification-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .verification-header {
            background: linear-gradient(135deg, var(--dark-color) 0%, #34495e 100%);
            color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            text-align: center;
        }

        .verification-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .verification-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
        }

        .verification-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .product-image-large {
            width: 100%;
            height: 250px;
            object-fit: cover;
            object-position: center;
        }

        .verification-content {
            padding: 1.5rem;
        }

        .product-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .product-details {
            margin-bottom: 1rem;
        }

        .product-detail {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .product-detail-label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .product-detail-value {
            color: var(--text-light);
        }

        .product-description {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .verification-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-verify {
            flex: 1;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .btn-approve {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .btn-approve:hover {
            background: linear-gradient(45deg, #218838, #1aa085);
            transform: translateY(-2px);
        }

        .btn-reject {
            background: linear-gradient(45deg, #dc3545, #fd7e14);
            color: white;
        }

        .btn-reject:hover {
            background: linear-gradient(45deg, #c82333, #e8680d);
            transform: translateY(-2px);
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-verified {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .message {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            text-align: center;
        }

        .message-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-light);
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 2rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .back-link:hover {
            color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .verification-grid {
                grid-template-columns: 1fr;
            }

            .verification-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="verification-container">
    <a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>

    <div class="verification-header">
        <h1><i class="fas fa-check-circle"></i> Verifikasi Produk</h1>
        <p>Periksa dan verifikasi produk baru sebelum ditampilkan</p>
    </div>

    <?php if (isset($message)): ?>
        <div class="message <?php echo strpos($message, 'berhasil') !== false ? 'message-success' : 'message-error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <div class="verification-grid">
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="verification-card">
                <img
                    src="../assets/images/<?php echo htmlspecialchars($row['gambar']); ?>"
                    alt="<?php echo htmlspecialchars($row['nama_produk']); ?>"
                    class="product-image-large"
                >

                <div class="verification-content">
                    <div class="status-badge status-<?php echo $row['status_verifikasi']; ?>">
                        <?php echo ucfirst($row['status_verifikasi']); ?>
                    </div>

                    <h3 class="product-title"><?php echo htmlspecialchars($row['nama_produk']); ?></h3>

                    <div class="product-details">
                        <div class="product-detail">
                            <span class="product-detail-label">Kategori:</span>
                            <span class="product-detail-value"><?php echo htmlspecialchars($row['kategori']); ?></span>
                        </div>
                        <div class="product-detail">
                            <span class="product-detail-label">Harga:</span>
                            <span class="product-detail-value">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></span>
                        </div>
                        <div class="product-detail">
                            <span class="product-detail-label">Stok:</span>
                            <span class="product-detail-value"><?php echo $row['stok']; ?> unit</span>
                        </div>
                    </div>

                    <div class="product-description">
                        <strong>Deskripsi:</strong><br>
                        <?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?>
                    </div>

                    <div class="verification-actions">
                        <form method="POST" style="flex: 1;">
                            <input type="hidden" name="id_produk" value="<?php echo $row['id_produk']; ?>">
                            <input type="hidden" name="status" value="verified">
                            <button type="submit" class="btn-verify btn-approve" onclick="return confirm('Apakah Anda yakin ingin menyetujui produk ini?')">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                        </form>

                        <form method="POST" style="flex: 1;">
                            <input type="hidden" name="id_produk" value="<?php echo $row['id_produk']; ?>">
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn-verify btn-reject" onclick="return confirm('Apakah Anda yakin ingin menolak produk ini?')">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-check-circle"></i>
            <h3>Semua produk sudah diverifikasi</h3>
            <p>Tidak ada produk yang perlu diverifikasi saat ini</p>
        </div>
    <?php endif; ?>
</div>

<?php
include '../inc/footer.php';
$koneksi->close();
?>
</body>
</html>
