<?php
/**
 * Halaman Laporan & Rekapitulasi Koleksi Perpustakaan
 * Memenuhi Peta Kompetensi Pertemuan 1–7:
 * - [P1] Tampilan web dinamis berbasis PHP
 * - [P2] OOP Class & Object Buku ($buku->hitungDenda(), $buku->getStatusPinjam())
 * - [P3] Variabel, Tipe Data, Scope & Array Asosiatif
 * - [P4] Operator Aritmatika, Perbandingan & Format String (htmlspecialchars, number_format)
 * - [P7] Perulangan FOR (Tabel Utama) & NESTED FOR (Rincian Eksemplar Rak / Rating)
 */

require_once __DIR__ . '/functions.php';
$koleksiBuku = require __DIR__ . '/data/sample.php';
$daftarObjekBuku = muatObjekBuku($koleksiBuku);

$totalKoleksi   = count($daftarObjekBuku);
$totalDenda     = 0;
$totalRating    = 0.0;
$bukuTerlambat  = 0;
$bukuDipinjam   = 0;
$bukuTersedia   = 0;
$dendaTertinggi = 0;
$judulDendaMax  = '-';

// [P7] Perhitungan agregat menggunakan perulangan for
for ($i = 0; $i < $totalKoleksi; $i++) {
    $item = $daftarObjekBuku[$i];
    $denda = $item->hitungDenda();
    $totalDenda += $denda;
    $totalRating += (float)$item->rating;

    if ($item->status === 'Tersedia') {
        $bukuTersedia++;
    } elseif ($item->status === 'Dipinjam') {
        $bukuDipinjam++;
    } elseif ($item->status === 'Terlambat') {
        $bukuTerlambat++;
    }

    if ($denda > $dendaTertinggi) {
        $dendaTertinggi = $denda;
        $judulDendaMax  = $item->judul;
    }
}

$rataRataRating = $totalKoleksi > 0 ? round($totalRating / $totalKoleksi, 2) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi - <?= amankanTeks(NAMA_PERPUSTAKAAN) ?></title>
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
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="proses.php">Proses Transaksi</a></li>
                    <li><a href="laporan.php" class="active">Laporan Koleksi</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="masthead">
        <div class="container">
            <h1>Laporan Inventaris dan Rekapitulasi Koleksi</h1>
            <p>
                Rekapitulasi sirkulasi, status ketersediaan rak, dan akumulasi sanksi keterlambatan koleksi buku.
            </p>
        </div>
    </section>

    <main class="container">

        <!-- Baris Ringkasan Data -->
        <div class="metrics-row">
            <div class="metric-panel">
                <div class="metric-label">Jumlah Buku</div>
                <div class="metric-number"><?= $totalKoleksi ?></div>
                <div class="metric-footnote"><?= $bukuTersedia ?> siap pinjam, <?= $bukuDipinjam + $bukuTerlambat ?> dipinjam</div>
            </div>
            <div class="metric-panel">
                <div class="metric-label">Buku Terlambat</div>
                <div class="metric-number"><?= $bukuTerlambat ?></div>
                <div class="metric-footnote">Tertinggi: <?= formatRupiah($dendaTertinggi) ?></div>
            </div>
            <div class="metric-panel">
                <div class="metric-label">Total Denda Koleksi</div>
                <div class="metric-number"><?= formatRupiah($totalDenda) ?></div>
                <div class="metric-footnote">Plafon per buku: <?= formatRupiah(DENDA_MAKSIMAL) ?></div>
            </div>
            <div class="metric-panel">
                <div class="metric-label">Rata-rata Rating</div>
                <div class="metric-number"><?= $rataRataRating ?> / 5.0</div>
                <div class="metric-footnote">Evaluasi pembaca katalog</div>
            </div>
        </div>

        <!-- 1. Tabel Utama Koleksi Buku (FOR Loop & OOP Class Buku) -->
        <section class="panel">
            <div class="panel-header">
                <span class="panel-title">Daftar Koleksi Buku</span>
            </div>
            <div class="panel-body">
                <div class="table-wrap">
                    <table class="table-catalog">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Judul Buku</th>
                                <th>Pengarang & Tahun</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Keterlambatan</th>
                                <th class="num-cell">Denda</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // [P7] Perulangan FOR untuk membentuk baris tabel data koleksi
                            for ($i = 0; $i < $totalKoleksi; $i++):
                                $buku = $daftarObjekBuku[$i];
                                $dendaNominal = $buku->hitungDenda();
                                
                                $tagClass = 'tag-neutral';
                                if ($buku->status === 'Tersedia') {
                                    $tagClass = 'tag-ready';
                                } elseif ($buku->status === 'Dipinjam') {
                                    $tagClass = 'tag-borrowed';
                                } elseif ($buku->status === 'Terlambat') {
                                    $tagClass = 'tag-late';
                                }
                            ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><code><?= amankanTeks($buku->kode) ?></code></td>
                                    <td><strong><?= amankanTeks(normalisasiJudul($buku->judul)) ?></strong></td>
                                    <td><?= amankanTeks($buku->pengarang) ?>, <?= $buku->tahun ?></td>
                                    <td><span class="tag-status tag-neutral"><?= amankanTeks($buku->kategori) ?></span></td>
                                    <td><span class="tag-status <?= $tagClass ?>"><?= amankanTeks($buku->status) ?></span></td>
                                    <td><?= hitungKeterlambatan($buku->hariTerlambat) ?></td>
                                    <td class="num-cell">
                                        <strong style="color: <?= $dendaNominal > 0 ? '#991b1b' : '#065f46' ?>;">
                                            <?= $buku->formatRupiahDenda() ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="star-rating"><?= $buku->getBintangRating() ?></span>
                                        <span style="font-size: 11.5px; color: var(--ink-muted);">(<?= $buku->rating ?>)</span>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background: #fafafa; font-weight: 700;">
                                <td colspan="7" style="text-align: right; padding: 12px 14px;">Total Akumulasi Denda:</td>
                                <td class="num-cell" style="color: #991b1b; font-size: 14px; padding: 12px 14px;">
                                    <?= formatRupiah($totalDenda) ?>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </section>

        <!-- 2. Visualisasi Slot Rak Unit & Rating (Nested FOR) -->
        <section class="panel">
            <div class="panel-header">
                <span class="panel-title">Ketersediaan Eksemplar Rak dan Penilaian Pembaca</span>
            </div>
            <div class="panel-body">
                <p style="font-size: 13.5px; color: var(--ink-muted); margin-bottom: 14px;">
                    Pemetaan status ketersediaan unit fisik buku pada rak perpustakaan beserta penilaian pembaca katalog.
                </p>
                <div class="table-wrap">
                    <table class="table-catalog">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Koleksi</th>
                                <th>Eksemplar Rak 1</th>
                                <th>Eksemplar Rak 2</th>
                                <th>Eksemplar Rak 3</th>
                                <th>Penilaian Pembaca</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // [P7] Nested loop FOR: iterasi buku dan slot unit eksemplar
                            for ($i = 0; $i < $totalKoleksi; $i++):
                                $buku = $daftarObjekBuku[$i];
                                $stokBuku = (int)($koleksiBuku[$i]['stok'] ?? 3);
                            ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><strong><?= amankanTeks($buku->judul) ?></strong></td>
                                    
                                    <?php
                                    for ($unit = 1; $unit <= 3; $unit++):
                                        if ($unit <= $stokBuku && $buku->status === 'Tersedia') {
                                            $statusUnit = '<span class="tag-status tag-ready">Unit ' . $unit . ' (Siap)</span>';
                                        } elseif ($unit <= $stokBuku && $buku->status === 'Dipinjam') {
                                            $statusUnit = ($unit === 1) ? '<span class="tag-status tag-borrowed">Unit 1 (Dipinjam)</span>' : '<span class="tag-status tag-ready">Unit ' . $unit . ' (Siap)</span>';
                                        } elseif ($unit <= $stokBuku && $buku->status === 'Terlambat') {
                                            $statusUnit = ($unit === 1) ? '<span class="tag-status tag-late">Unit 1 (Terlambat)</span>' : '<span class="tag-status tag-ready">Unit ' . $unit . ' (Siap)</span>';
                                        } else {
                                            $statusUnit = '<span style="color: var(--ink-muted);">-</span>';
                                        }
                                        echo '<td>' . $statusUnit . '</td>';
                                    endfor;
                                    ?>

                                    <td>
                                        <span class="star-rating">
                                            <?php
                                            $bintangUtuh = (int)round($buku->rating);
                                            for ($s = 1; $s <= 5; $s++):
                                                echo ($s <= $bintangUtuh) ? '★' : '☆';
                                            endfor;
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
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
