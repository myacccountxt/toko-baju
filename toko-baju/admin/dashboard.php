<?php
include '../koneksi.php';

// Ambil semua data produk
$query = "SELECT id_produk, nama_produk, harga, stok, kategori, gambar FROM produk ORDER BY id_produk DESC";
$result = $koneksi->query($query);

// Hitung statistik
$totalProducts = $result->num_rows;
$totalValue = 0;
$lowStock = 0;
$categories = [];

$result->data_seek(0); // Reset pointer
while($row = $result->fetch_assoc()) {
    $totalValue += $row['harga'] * $row['stok'];
    if ($row['stok'] < 10) $lowStock++;
    if (!in_array($row['kategori'], $categories)) $categories[] = $row['kategori'];
}
$categoryCount = count($categories);
$result->data_seek(0); // Reset pointer again
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Manajemen Produk</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <script src="../assets/js/script.js"></script>
    <style>
        /* Enhanced Admin Dashboard Styles */
        .admin-dashboard {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--dark-color) 0%, #34495e 100%);
            color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="admin-pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23admin-pattern)"/></svg>');
            pointer-events: none;
        }

        .dashboard-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .dashboard-header p {
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-dark);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dashboard-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .control-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary, .btn-secondary {
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: var(--shadow);
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }

        .btn-secondary {
            background: var(--light-color);
            color: var(--dark-color);
            border: 2px solid #e1e5e9;
        }

        .btn-secondary:hover {
            background: var(--dark-color);
            color: white;
            border-color: var(--dark-color);
        }

        .search-filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .search-filter-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        #searchInput {
        background: var(--dark-color); /* Latar belakang input gelap */
        color: white; /* Teks input putih */
    }

     .filter-input {
        padding: 0.75rem;
        border: 2px solid var(--dark-color); /* Border gelap */
        border-radius: var(--border-radius);
        font-size: 1rem;
        transition: var(--transition);
    }

    .filter-input::placeholder {
        color: var(--light-color); /* Placeholder warna terang */
    }

        .filter-input:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(0, 191, 255, 0.2);
        }

        .filter-select {
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: var(--border-radius);
            background: white;
            cursor: pointer;
        }

        .products-table-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
        }

.products-table thead {
        background: linear-gradient(45deg, var(--dark-color), #34495e); /* Diubah ke warna gelap yang konsisten */
        color: white;
    }

        .products-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .products-table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: middle;
        }

        .products-table tbody tr {
            transition: var(--transition);
        }

        .products-table tbody tr:hover {
            background: #f8f9fa;
        }

        .product-image-cell {
            width: 60px;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            object-position: center;
            border-radius: var(--border-radius);
            border: 2px solid #e1e5e9;
        }

        .product-name {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .product-category {
            display: inline-block;
            background: var(--secondary-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .price-cell {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .stock-cell {
            font-weight: 600;
        }

        .stock-low {
            color: #dc3545;
        }

        .stock-medium {
            color: #ffc107;
        }

        .stock-good {
            color: #28a745;
        }

        .actions-cell {
            text-align: right;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            color: #008cba;
            font-size: 0.85rem;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .action-btn-edit{
            background: var(--secondary-color); /* Biru Cerah untuk Edit */
             color: white;
        }

        .action-btn-edit {
            background: var(--secondary-color);
            color: white;
        }

        .action-btn-edit:hover {
            background: #008cba;
            transform: translateY(-1px);
        }

        .action-btn-delete {
            background: #dc3545;
            color: white;
        }

        .action-btn-delete:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-light);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .status-message {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideIn 0.5s ease-out;
        }

        .status-success {
            background: linear-gradient(45deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .status-error {
            background: linear-gradient(45deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .status-warning {
            background: linear-gradient(45deg, #fff3cd, #ffeaa7);
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-header h1 {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }

            .search-filter-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .dashboard-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .control-buttons {
                justify-content: center;
            }

            .products-table {
                font-size: 0.9rem;
            }

            .products-table th,
            .products-table td {
                padding: 0.75rem 0.5rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }

            .product-image-cell {
                width: 50px;
            }

            .product-image {
                width: 40px;
                height: 40px;
            }
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="admin-dashboard">
    <div class="dashboard-header">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h1>
        <p>Kelola produk fashion boutique Anda dengan mudah</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="color: var(--primary-color);">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-number"><?php echo $totalProducts; ?></div>
            <div class="stat-label">Total Produk</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="color: var(--secondary-color);">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-number">Rp <?php echo number_format($totalValue, 0, ',', '.'); ?></div>
            <div class="stat-label">Total Nilai</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="color: #ffc107;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-number"><?php echo $lowStock; ?></div>
            <div class="stat-label">Stok Rendah</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="color: #28a745;">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-number"><?php echo $categoryCount; ?></div>
            <div class="stat-label">Kategori</div>
        </div>
    </div>

    <!-- Status Messages -->
    <?php if (isset($_GET['status'])): ?>
        <div class="status-message status-success">
            <i class="fas fa-check-circle"></i>
            <span><?php echo htmlspecialchars($_GET['status']); ?></span>
        </div>
    <?php endif; ?>

    <!-- Controls -->
    <div class="dashboard-controls">
        <div class="control-buttons">
            <a href="tambah_produk.php" class="btn-primary">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
            <a href="verifikasi_produk.php" class="btn-primary">
                <i class="fas fa-check-circle"></i> Verifikasi Produk
            </a>
            <a href="../index.php" class="btn-secondary">
                <i class="fas fa-eye"></i> Lihat Website
            </a>
        </div>

        <div class="current-time">
            <i class="fas fa-clock"></i>
            <span id="current-time"></span>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter-section">
        <div class="search-filter-grid">
            <input type="text" id="searchInput" class="filter-input" placeholder="Cari produk..." onkeyup="filterProducts()">
            <select id="categoryFilter" class="filter-select" onchange="filterProducts()">
                <option value="">Semua Kategori</option>
                <?php
                $result->data_seek(0);
                $categories = [];
                while($row = $result->fetch_assoc()) {
                    if (!in_array($row['kategori'], $categories)) {
                        $categories[] = $row['kategori'];
                        echo "<option value='" . htmlspecialchars($row['kategori']) . "'>" . htmlspecialchars($row['kategori']) . "</option>";
                    }
                }
                $result->data_seek(0);
                ?>
            </select>
            <select id="stockFilter" class="filter-select" onchange="filterProducts()">
                <option value="">Semua Stok</option>
                <option value="low">Stok Rendah (< 10)</option>
                <option value="medium">Stok Sedang (10-50)</option>
                <option value="high">Stok Tinggi (> 50)</option>
            </select>
            <button onclick="resetFilters()" class="btn-secondary" style="padding: 0.75rem 1rem;">
                <i class="fas fa-undo"></i> Reset
            </button>
        </div>
    </div>

    <!-- Products Table -->
    <div class="products-table-container">
        <?php if ($result->num_rows > 0): ?>
            <table class="products-table" id="productsTable">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr data-category="<?php echo htmlspecialchars($row['kategori']); ?>" data-stock="<?php echo $row['stok']; ?>">
                        <td class="product-image-cell">
                            <?php if ($row['gambar'] && file_exists("../assets/images/" . $row['gambar'])): ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>" class="product-image">
                            <?php else: ?>
                                <div class="product-image" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="product-name"><?php echo htmlspecialchars($row['nama_produk']); ?></div>
                            <div class="product-category"><?php echo htmlspecialchars($row['kategori']); ?></div>
                        </td>
                        <td class="price-cell">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td class="stock-cell">
                            <span class="<?php
                                if ($row['stok'] < 10) echo 'stock-low';
                                elseif ($row['stok'] < 50) echo 'stock-medium';
                                else echo 'stock-good';
                            ?>">
                                <?php echo $row['stok']; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                        <td class="actions-cell">
                            <div class="action-buttons">
                                <a href="edit_produk.php?id=<?php echo $row['id_produk']; ?>" class="action-btn action-btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="hapus_produk.php?id=<?php echo $row['id_produk']; ?>"
                                   class="action-btn action-btn-delete"
                                   onclick="return confirmDelete(this)">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>Belum ada produk</h3>
                <p>Mulai tambahkan produk pertama Anda</p>
                <a href="tambah_produk.php" class="btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i> Tambah Produk Pertama
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        document.getElementById('current-time').textContent = timeString;
    }

    updateTime();
    setInterval(updateTime, 60000); // Update every minute

    // Enhanced delete confirmation
    window.confirmDelete = function(link) {
        if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
            showLoading();
            return true;
        }
        return false;
    };

    // Loading functions
    function showLoading() {
        document.getElementById('loadingOverlay').classList.add('active');
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').classList.remove('active');
    }

    // Filter functions
    window.filterProducts = function() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const categoryFilter = document.getElementById('categoryFilter').value;
        const stockFilter = document.getElementById('stockFilter').value;
        const rows = document.querySelectorAll('#productsTable tbody tr');

        rows.forEach(row => {
            const productName = row.cells[1].textContent.toLowerCase();
            const category = row.getAttribute('data-category');
            const stock = parseInt(row.getAttribute('data-stock'));
            let show = true;

            // Search filter
            if (searchTerm && !productName.includes(searchTerm)) {
                show = false;
            }

            // Category filter
            if (categoryFilter && category !== categoryFilter) {
                show = false;
            }

            // Stock filter
            if (stockFilter) {
                if (stockFilter === 'low' && stock >= 10) show = false;
                if (stockFilter === 'medium' && (stock < 10 || stock > 50)) show = false;
                if (stockFilter === 'high' && stock <= 50) show = false;
            }

            row.style.display = show ? '' : 'none';

            // Add animation
            if (show) {
                row.style.animation = 'fadeIn 0.3s ease-out';
            }
        });
    };

    window.resetFilters = function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('stockFilter').value = '';
        filterProducts();
    };

    // Auto-hide status messages
    const statusMessage = document.querySelector('.status-message');
    if (statusMessage) {
        setTimeout(() => {
            statusMessage.style.animation = 'slideOut 0.5s ease-out';
            setTimeout(() => {
                statusMessage.remove();
            }, 500);
        }, 5000);
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + N for new product
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            window.location.href = 'tambah_produk.php';
        }

        // Ctrl/Cmd + F for search focus
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            document.getElementById('searchInput').focus();
        }
    });

    // Add tooltips to action buttons
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach(btn => {
        btn.setAttribute('title', btn.textContent.trim());
    });

    // Animate stats on load
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => {
        const target = parseInt(stat.textContent.replace(/[^\d]/g, ''));
        if (!isNaN(target)) {
            animateNumber(stat, 0, target, 1000);
        }
    });

    function animateNumber(element, start, end, duration) {
        const startTime = performance.now();
        const endTime = startTime + duration;

        function update(currentTime) {
            if (currentTime >= endTime) {
                element.textContent = end === target ? formatNumber(end) : end;
                return;
            }

            const progress = (currentTime - startTime) / duration;
            const current = Math.floor(start + (end - start) * progress);
            element.textContent = formatNumber(current);

            requestAnimationFrame(update);
        }

        function formatNumber(num) {
            if (element.textContent.includes('Rp')) {
                return 'Rp ' + num.toLocaleString('id-ID');
            }
            return num;
        }

        requestAnimationFrame(update);
    }

    // Add slideOut animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-10px); }
        }
    `;
    document.head.appendChild(style);

    console.log('Admin dashboard interactive features loaded successfully!');
});
</script>

</body>
</html>
<?php $koneksi->close(); ?>
