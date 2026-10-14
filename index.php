<?php
/**
 * Halaman Utama (Dashboard & Navigasi) - Sistem Informasi Perpustakaan
 * [P1] Paradigma Pemrograman PHP & Sintaks Dasar
 */

require_once __DIR__ . '/functions.php';
$koleksiBuku = require __DIR__ . '/data/sample.php';
$daftarObjekBuku = muatObjekBuku($koleksiBuku);

// [P3 & P4] Kalkulasi metrik operasional perpustakaan
$totalBuku      = count($koleksiBuku);
$jumlahTersedia = hitungJumlahTersedia($koleksiBuku);
$jumlahDipinjam = $totalBuku - $jumlahTersedia;
$totalDenda     = hitungTotalDenda($koleksiBuku);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sirkulasi Koleksi - <?= amankanTeks(NAMA_PERPUSTAKAAN) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header class="site-header">
        <div class="container nav-wrapper">
            <a href="index.php" class="brand-identity">
                <strong><?= amankanTeks(NAMA_PERPUSTAKAAN) ?></strong>
                <span>Sistem Sirkulasi & Koleksi Buku</span>
            </a>
            <nav>
                <ul class="site-nav">
                    <li><a href="index.php" class="active">Beranda</a></li>
                    <li><a href="proses.php">Proses Transaksi</a></li>
                    <li><a href="laporan.php">Laporan Koleksi</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="masthead">
        <div class="container">
            <h1>Sirkulasi dan Inventaris Buku Perpustakaan</h1>
            <p>
                Layanan pengelolaan katalog koleksi, pencarian buku, perhitungan masa pinjam, serta pencatatan sanksi keterlambatan perpustakaan.
            </p>
            <div class="action-bar">
                <a href="proses.php" class="btn btn-primary">Pencarian & Hitung Denda</a>
                <a href="laporan.php" class="btn btn-secondary">Tabel Rekapitulasi Koleksi</a>
            </div>
        </div>
    </section>

    <main class="container">

        <!-- Baris Metrik Operasional -->
        <div class="metrics-row">
            <div class="metric-panel">
                <div class="metric-label">Total Judul Koleksi</div>
                <div class="metric-number"><?= $totalBuku ?></div>
                <div class="metric-footnote">Judul terdaftar pada katalog</div>
            </div>
            <div class="metric-panel">
                <div class="metric-label">Buku Siap Pinjam</div>
                <div class="metric-number"><?= $jumlahTersedia ?></div>
                <div class="metric-footnote">Tersedia di rak perpustakaan</div>
            </div>
            <div class="metric-panel">
                <div class="metric-label">Buku Sedang Dipinjam</div>
                <div class="metric-number"><?= $jumlahDipinjam ?></div>
                <div class="metric-footnote">Tercatat dalam masa sirkulasi</div>
            </div>
            <div class="metric-panel">
                <div class="metric-label">Akumulasi Denda Berjalan</div>
                <div class="metric-number"><?= formatRupiah($totalDenda) ?></div>
                <div class="metric-footnote">Total sanksi keterlambatan</div>
            </div>
        </div>

        <!-- Sampel Koleksi Pilihan -->
        <section class="panel">
            <div class="panel-header">
                <span class="panel-title">Koleksi Buku Terpopuler</span>
                <a href="laporan.php" class="btn btn-secondary" style="font-size: 12px; padding: 4px 10px;">Buka Semua Koleksi</a>
            </div>
            <div class="panel-body">
                <div class="cards-catalog">
                    <?php
                    // [P7] Iterasi kartu buku pilihan
                    for ($i = 0; $i < min(3, count($daftarObjekBuku)); $i++):
                        $buku = $daftarObjekBuku[$i];
                    ?>
                        <div class="book-card">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <code><?= amankanTeks($buku->kode) ?></code>
                                    <span class="tag-status <?= $buku->status === 'Tersedia' ? 'tag-ready' : ($buku->status === 'Dipinjam' ? 'tag-borrowed' : 'tag-late') ?>">
                                        <?= amankanTeks($buku->status) ?>
                                    </span>
                                </div>
                                <h4><?= amankanTeks($buku->judul) ?></h4>
                                <div class="book-card-meta">
                                    <?= amankanTeks($buku->pengarang) ?>, terbit <?= $buku->tahun ?>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px solid var(--line-subtle);">
                                <span class="star-rating"><?= $buku->getBintangRating() ?></span>
                                <span style="font-size: 12px; font-weight: 600; color: var(--ink-muted);"><?= amankanTeks($buku->kategori) ?></span>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </section>

        <!-- Profil Tim Pengembang -->
        <section class="panel">
            <div class="panel-header">
                <span class="panel-title">Tim Pengembang Perpustakaan</span>
                <span class="tag-status tag-neutral">Kelompok 7</span>
            </div>
            <div class="panel-body">
                <div class="team-grid">
                    <div class="team-card">
                        <div class="team-card-name">Alfin Romi Setiawan</div>
                        <div class="team-card-npm">NPM: 4524210009</div>
                        <div class="team-card-role">Koordinator / Analis Sistem</div>
                    </div>
                    <div class="team-card">
                        <div class="team-card-name">Alifian Revan Arnanta</div>
                        <div class="team-card-npm">NPM: 4524210010</div>
                        <div class="team-card-role">Pengembang Logika Prosedural</div>
                    </div>
                    <div class="team-card">
                        <div class="team-card-name">Az-Zahra Putri</div>
                        <div class="team-card-npm">NPM: 4524210018</div>
                        <div class="team-card-role">Pengembang Pemodelan OOP</div>
                    </div>
                    <div class="team-card">
                        <div class="team-card-name">Rakha Hafidz Fauzan</div>
                        <div class="team-card-npm">NPM: 4524210080</div>
                        <div class="team-card-role">Pengujian & Dokumentasi</div>
                    </div>
                    <div class="team-card">
                        <div class="team-card-name">Putri Wahyu Dwi N.</div>
                        <div class="team-card-npm">NPM: 4524210082</div>
                        <div class="team-card-role">Desain Antarmuka & Integrasi</div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="site-footer">
        <div class="container">
            <p><?= amankanTeks(NAMA_PERPUSTAKAAN) ?> &bull; Sistem Informasi Perpustakaan</p>
        </div>
    </footer>

</body>
</html>
