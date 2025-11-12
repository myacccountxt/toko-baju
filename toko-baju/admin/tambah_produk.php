<?php
include '../koneksi.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = $koneksi->real_escape_string($_POST['nama_produk']);
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $kategori = $koneksi->real_escape_string($_POST['kategori']);
    $deskripsi = $koneksi->real_escape_string($_POST['deskripsi']);

    // Upload gambar
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../assets/images/";
        $file_extension = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");

        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                $gambar = $new_filename;
            } else {
                $message = "Gagal mengupload gambar.";
            }
        } else {
            $message = "Format gambar tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.";
        }
    }

    if (empty($message)) {
        $sql = "INSERT INTO produk (nama_produk, harga, stok, kategori, deskripsi, gambar, status_verifikasi)
                VALUES ('$nama_produk', $harga, $stok, '$kategori', '$deskripsi', '$gambar', 'pending')";

        if ($koneksi->query($sql) === TRUE) {
            $message = "Produk berhasil ditambahkan dan menunggu verifikasi admin.";
        } else {
            $message = "Error: " . $koneksi->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - Admin</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <script src="../assets/js/script.js"></script>
    <style>
        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .form-header {
            background: linear-gradient(135deg, var(--dark-color) 0%, #34495e 100%);
            color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            text-align: center;
        }

        .product-form {
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(0, 191, 255, 0.2);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .btn-submit {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }

        .btn-cancel {
            background: var(--light-color);
            color: var(--dark-color);
            padding: 0.75rem 2rem;
            border: 2px solid #e1e5e9;
            border-radius: var(--border-radius);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-cancel:hover {
            background: var(--dark-color);
            color: white;
            border-color: var(--dark-color);
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

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border: 2px dashed #e1e5e9;
            border-radius: var(--border-radius);
            background: #f8f9fa;
            cursor: pointer;
            transition: var(--transition);
        }

        .file-input-label:hover {
            border-color: var(--secondary-color);
            background: #e9ecef;
        }

        .file-input-label i {
            font-size: 2rem;
            margin-right: 0.5rem;
            color: var(--text-light);
        }

        .file-name {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <a href="dashboard.php" style="display: inline-block; margin-bottom: 2rem; color: var(--primary-color); text-decoration: none; font-weight: 500;">
        ← Kembali ke Dashboard
    </a>

    <div class="form-header">
        <h1><i class="fas fa-plus"></i> Tambah Produk Baru</h1>
        <p>Produk akan ditambahkan dengan status pending dan perlu diverifikasi admin</p>
    </div>

    <?php if (!empty($message)): ?>
        <div class="message <?php echo strpos($message, 'berhasil') !== false ? 'message-success' : 'message-error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form class="product-form" method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="nama_produk">Nama Produk *</label>
                <input type="text" id="nama_produk" name="nama_produk" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="kategori">Kategori *</label>
                <select id="kategori" name="kategori" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Baju">Baju</option>
                    <option value="Celana">Celana</option>
                    <option value="Rok">Rok</option>
                    <option value="Jaket">Jaket</option>
                    <option value="Sepatu">Sepatu</option>
                    <option value="Tas">Tas</option>
                    <option value="Aksesoris">Aksesoris</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="harga">Harga (Rp) *</label>
                <input type="number" id="harga" name="harga" class="form-input" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="stok">Stok *</label>
                <input type="number" id="stok" name="stok" class="form-input" min="0" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="deskripsi">Deskripsi *</label>
            <textarea id="deskripsi" name="deskripsi" class="form-textarea" required></textarea>
        </div>

        <div class="form-group">
            <label class="form-label" for="gambar">Gambar Produk</label>
            <div class="file-input-wrapper">
                <input type="file" id="gambar" name="gambar" class="file-input" accept="image/*">
                <label for="gambar" class="file-input-label">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Klik untuk memilih gambar</span>
                </label>
            </div>
            <div id="file-name" class="file-name"></div>
        </div>

        <div class="form-actions">
            <a href="dashboard.php" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Tambah Produk</button>
        </div>
    </form>
</div>

<script>
document.getElementById('gambar').addEventListener('change', function(e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : '';
    document.getElementById('file-name').textContent = fileName;
});
</script>

<?php
include '../inc/footer.php';
$koneksi->close();
?>
</body>
</html>
