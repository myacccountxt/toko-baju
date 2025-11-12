<?php
include 'koneksi.php';
include 'inc/header.php';
?>

<div class="hero-section">
    <div class="hero-content">
        <h2>Tentang Kami</h2>
        <p>Mengenal lebih dekat dengan Fashion Boutique</p>
    </div>
</div>

<div class="container tentang-container">
    <div class="tentang-grid">
        <div class="tentang-content">
            <h3>Siapa Kami?</h3>
            <p>
                Fashion Boutique adalah toko baju online terpercaya yang telah melayani pelanggan
                sejak tahun 2020. Kami berkomitmen untuk menyediakan koleksi pakaian fashion
                terkini dengan kualitas terbaik dan harga terjangkau.
            </p>

            <h3>Visi Kami</h3>
            <p>
                Menjadi destinasi utama fashion online di Indonesia yang menghadirkan tren
                terkini dengan pelayanan prima dan pengalaman berbelanja yang menyenangkan.
            </p>

            <h3>Misi Kami</h3>
            <ul>
                <li>Menyediakan produk fashion berkualitas tinggi</li>
                <li>Memberikan pelayanan pelanggan yang excellent</li>
                <li>Menghadirkan tren fashion terkini</li>
                <li>Membangun komunitas fashion yang positif</li>
            </ul>

            <h3>Kenapa Memilih Kami?</h3>
            <div class="keunggulan-grid">
                <div class="keunggulan-item">
                    <i class="fas fa-shipping-fast"></i>
                    <h4>Pengiriman Cepat</h4>
                    <p>Pengiriman ke seluruh Indonesia dengan jasa terpercaya</p>
                </div>
                <div class="keunggulan-item">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Produk Berkualitas</h4>
                    <p>Garansi kualitas dan kepuasan pelanggan</p>
                </div>
                <div class="keunggulan-item">
                    <i class="fas fa-headset"></i>
                    <h4>Customer Service</h4>
                    <p>Dukungan pelanggan 24/7 siap membantu Anda</p>
                </div>
                <div class="keunggulan-item">
                    <i class="fas fa-tags"></i>
                    <h4>Harga Terjangkau</h4>
                    <p>Kualitas premium dengan harga bersaing</p>
                </div>
            </div>
        </div>

        <div class="tentang-sidebar">
            <div class="contact-info">
                <h3>Hubungi Kami</h3>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>Alamat</strong><br>
                        Jl. Fashion Street No. 123<br>
                        Jakarta Pusat, 10110
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <strong>Telepon</strong><br>
                        +62 21 1234 5678
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Email</strong><br>
                        info@fashionboutique.com
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Jam Operasional</strong><br>
                        Senin - Minggu: 09:00 - 21:00 WIB
                    </div>
                </div>
            </div>

            <div class="social-media">
                <h3>Ikuti Kami</h3>
                <div class="social-links">
                    <a href="#" class="social-link">
                        <i class="fab fa-facebook-f"></i>
                        <span>Facebook</span>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-instagram"></i>
                        <span>Instagram</span>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-twitter"></i>
                        <span>Twitter</span>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-tiktok"></i>
                        <span>TikTok</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'inc/footer.php';
$koneksi->close();
?>
