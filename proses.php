<?php
/**
 * Halaman Pemrosesan Transaksi Perpustakaan
 * Memenuhi Peta Kompetensi Pertemuan 1–7:
 * - [P1] Pemrosesan form dinamis berbasis PHP
 * - [P2] Fungsi Prosedural & OOP Buku
 * - [P3] Variabel, Tipe Data, Scope, Casting, Konstanta
 * - [P4] Operator Aritmatika, Perbandingan, Logika, Ternary & Sanitasi String
 * - [P5] Kontrol Perulangan WHILE (Pencarian & Antrean)
 * - [P6] Kontrol Perulangan DO-WHILE (Siklus Menu Transaksi)
 */

require_once __DIR__ . '/functions.php';
$koleksiBuku = require __DIR__ . '/data/sample.php';
$daftarObjekBuku = muatObjekBuku($koleksiBuku);

$keywordCari        = '';
$hasilPencarian     = null;
$jenisPencarian     = '';
$strukTransaksi     = null;
$hasilMenu          = null;
$logAntreanWhile    = null;

// [P4 & P5] Pencarian data buku menggunakan perulangan while
if (isset($_GET['aksi']) && $_GET['aksi'] === 'cari') {
    $keywordCari = trim((string)($_GET['keyword'] ?? ''));
    $jenisPencarian = $_GET['metode'] ?? 'judul';

    if ($keywordCari !== '') {
        if ($jenisPencarian === 'kode') {
            $bukuKetemu = cariBukuByKode($koleksiBuku, $keywordCari);
            $hasilPencarian = $bukuKetemu ? [$bukuKetemu] : [];
        } else {
            $hasilPencarian = cariBukuByJudul($koleksiBuku, $keywordCari);
        }
    }
}

// [P3 & P4] Kalkulasi denda dan penerbitan bukti transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'hitung_transaksi') {
    $namaPeminjam   = normalisasiJudul((string)($_POST['nama_peminjam'] ?? 'Pengunjung'));
    $kodeBukuPilih  = strtoupper(trim((string)($_POST['kode_buku'] ?? '')));
    $durasiPinjam   = ambilHariTerlambat($_POST['durasi_hari'] ?? 0);
    $tarifDenda     = (int)($_POST['tarif_denda'] ?? DENDA_PER_HARI);
    $isMemberVIP    = isset($_POST['member_vip']) && (bool)$_POST['member_vip'];

    $bukuTerpilih = null;
    $idx = 0;
    while ($idx < count($daftarObjekBuku)) {
        if ($daftarObjekBuku[$idx]->kode === $kodeBukuPilih) {
            $bukuTerpilih = $daftarObjekBuku[$idx];
            break;
        }
        $idx++;
    }

    if ($bukuTerpilih) {
        $batasPinjam     = BATAS_PINJAM_HARI;
        $hariTerlambat   = max(0, $durasiPinjam - $batasPinjam);
        $dendaDasar      = hitungDenda($hariTerlambat, $tarifDenda);
        
        $persenDiskon    = $isMemberVIP ? 20 : 0;
        $potonganDiskon  = (float)($dendaDasar * $persenDiskon / 100);
        $totalBayarDenda = (int)($dendaDasar - $potonganDiskon);

        $strukTransaksi = [
            'no_transaksi'     => 'TRX-' . date('Ymd') . '-' . rand(100, 999),
            'nama_peminjam'    => $namaPeminjam,
            'kode_buku'        => $bukuTerpilih->kode,
            'judul_buku'       => $bukuTerpilih->judul,
            'pengarang'        => $bukuTerpilih->pengarang,
            'durasi_pinjam'    => $durasiPinjam,
            'batas_standar'    => $batasPinjam,
            'hari_terlambat'   => $hariTerlambat,
            'rincian_waktu'    => hitungKeterlambatan($hariTerlambat),
            'tarif_per_hari'   => $tarifDenda,
            'denda_dasar'      => $dendaDasar,
            'is_vip'           => $isMemberVIP,
            'persen_diskon'    => $persenDiskon,
            'potongan_diskon'  => $potonganDiskon,
            'total_bayar'      => $totalBayarDenda,
            'status_transaksi' => $hariTerlambat > 0 ? 'Dikenakan Sanksi Denda' : 'Tepat Waktu (Bebas Denda)'
        ];
    }
}

// [P6] Eksekusi menu sirkulasi menggunakan perulangan do-while
if (isset($_POST['aksi']) && $_POST['aksi'] === 'simulasi_dowhile') {
    $pilihan = (int)($_POST['menu_pilihan'] ?? 1);
    $hasilMenu = eksekusiMenuSirkulasi($koleksiBuku, $pilihan);
}

// [P5] Pemrosesan antrean pengembalian menggunakan perulangan while
if (isset($_POST['aksi']) && $_POST['aksi'] === 'simulasi_antrean') {
    $sampelAntrean = [
        ['kode' => 'BK001', 'judul' => 'Belajar Pemrograman PHP Modern', 'hari_terlambat' => 3],
        ['kode' => 'BK007', 'judul' => 'Laut Bercerita', 'hari_terlambat' => 4],
        ['kode' => 'BK010', 'judul' => 'Before the Coffee Gets Cold', 'hari_terlambat' => 55],
    ];
    $logAntreanWhile = prosesAntreanPengembalian($sampelAntrean);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Transaksi - <?= amankanTeks(NAMA_PERPUSTAKAAN) ?></title>
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
                    <li><a href="proses.php" class="active">Proses Transaksi</a></li>
                    <li><a href="laporan.php">Laporan Koleksi</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="masthead">
        <div class="container">
            <h1>Pemrosesan Sirkulasi dan Transaksi</h1>
            <p>
                Layanan pencarian katalog, kalkulator denda keterlambatan sirkulasi, menu layanan transaksi, dan pemrosesan antrean pengembalian buku.
            </p>
        </div>
    </section>

    <main class="container">

        <!-- 1. Pencarian Buku Cepat -->
        <section class="panel">
            <div class="panel-header">
                <span class="panel-title">Pencarian Koleksi Buku</span>
            </div>
            <div class="panel-body">
                <form action="proses.php" method="GET" class="field-grid" style="align-items: flex-end; margin-bottom: 16px;">
                    <input type="hidden" name="aksi" value="cari">
                    <div class="field-group" style="grid-column: span 2; margin-bottom: 0;">
                        <label class="field-label" for="keyword">Kata kunci pencarian</label>
                        <input type="text" id="keyword" name="keyword" class="field-input" 
                               placeholder="Ketik judul buku atau kode seperti BK003"
                               value="<?= amankanTeks($keywordCari) ?>" required>
                    </div>
                    <div class="field-group" style="margin-bottom: 0;">
                        <label class="field-label" for="metode">Metode pencarian</label>
                        <select id="metode" name="metode" class="field-select">
                            <option value="judul" <?= $jenisPencarian === 'judul' ? 'selected' : '' ?>>Judul buku</option>
                            <option value="kode" <?= $jenisPencarian === 'kode' ? 'selected' : '' ?>>Kode buku</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" style="height: 38px;">Cari Buku</button>
                    </div>
                </form>

                <?php if ($hasilPencarian !== null): ?>
                    <div style="margin-top: 18px;">
                        <?php if (empty($hasilPencarian)): ?>
                            <div style="padding: 12px 14px; background: #fffbeb; border: 1px solid #fde68a; border-radius: var(--radius-sm); font-size: 13.5px; color: #92400e;">
                                Koleksi dengan kata kunci "<?= amankanTeks($keywordCari) ?>" tidak ditemukan di katalog.
                            </div>
                        <?php else: ?>
                            <div style="margin-bottom: 10px; font-size: 13px; color: var(--ink-muted);">
                                Ditemukan <?= count($hasilPencarian) ?> buku untuk kata kunci "<?= amankanTeks($keywordCari) ?>":
                            </div>
                            <div class="table-wrap">
                                <table class="table-catalog">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Judul Buku</th>
                                            <th>Pengarang</th>
                                            <th>Kategori</th>
                                            <th>Status</th>
                                            <th>Keterlambatan</th>
                                            <th class="num-cell">Estimasi Denda</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $k = 0;
                                        while ($k < count($hasilPencarian)):
                                            $b = $hasilPencarian[$k];
                                            $dendaBuku = hitungDenda((int)($b['hari_terlambat'] ?? 0));
                                        ?>
                                            <tr>
                                                <td><code><?= amankanTeks($b['kode']) ?></code></td>
                                                <td><strong><?= amankanTeks(normalisasiJudul($b['judul'])) ?></strong></td>
                                                <td><?= amankanTeks($b['pengarang'] ?? '-') ?> (<?= $b['tahun'] ?? '-' ?>)</td>
                                                <td><span class="tag-status tag-neutral"><?= amankanTeks($b['kategori'] ?? 'Umum') ?></span></td>
                                                <td>
                                                    <span class="tag-status <?= ($b['status'] ?? '') === 'Tersedia' ? 'tag-ready' : (($b['status'] ?? '') === 'Dipinjam' ? 'tag-borrowed' : 'tag-late') ?>">
                                                        <?= amankanTeks($b['status'] ?? 'Tersedia') ?>
                                                    </span>
                                                </td>
                                                <td><?= hitungKeterlambatan((int)($b['hari_terlambat'] ?? 0)) ?></td>
                                                <td class="num-cell"><strong style="color: #991b1b;"><?= formatRupiah($dendaBuku) ?></strong></td>
                                            </tr>
                                        <?php 
                                            $k++;
                                        endwhile; 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- 2. Kalkulator Pengembalian & Penerbitan Bukti Transaksi -->
        <section class="panel">
            <div class="panel-header">
                <span class="panel-title">Kalkulator Pengembalian dan Bukti Transaksi</span>
            </div>
            <div class="panel-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
                    <div>
                        <form action="proses.php" method="POST">
                            <input type="hidden" name="aksi" value="hitung_transaksi">
                            
                            <div class="field-group">
                                <label class="field-label" for="nama_peminjam">Nama anggota peminjam</label>
                                <input type="text" id="nama_peminjam" name="nama_peminjam" class="field-input" 
                                       value="Alfin Romi Setiawan" required>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="kode_buku">Pilih judul buku</label>
                                <select id="kode_buku" name="kode_buku" class="field-select" required>
                                    <?php foreach ($daftarObjekBuku as $objBuku): ?>
                                        <option value="<?= amankanTeks($objBuku->kode) ?>">
                                            <?= amankanTeks($objBuku->kode) ?> - <?= amankanTeks($objBuku->judul) ?> (<?= $objBuku->status ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="field-grid">
                                <div class="field-group">
                                    <label class="field-label" for="durasi_hari">Durasi pinjam aktual (hari)</label>
                                    <input type="number" id="durasi_hari" name="durasi_hari" class="field-input" 
                                           value="12" min="1" max="180" required>
                                    <div class="field-hint">Batas waktu standar: <?= BATAS_PINJAM_HARI ?> hari</div>
                                </div>
                                <div class="field-group">
                                    <label class="field-label" for="tarif_denda">Tarif denda harian (Rp)</label>
                                    <input type="number" id="tarif_denda" name="tarif_denda" class="field-input" 
                                           value="<?= DENDA_PER_HARI ?>" step="500" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-dark" style="width: 100%;">
                                Hitung Denda & Buat Bukti Pengembalian
                            </button>
                        </form>
                    </div>

                    <div>
                        <?php if ($strukTransaksi): ?>
                            <div class="receipt-card">
                                <div class="receipt-header">
                                    <h3><?= amankanTeks(NAMA_PERPUSTAKAAN) ?></h3>
                                    <p>Bukti Pengembalian dan Perhitungan Sanksi Sirkulasi</p>
                                    <code><?= $strukTransaksi['no_transaksi'] ?></code>
                                </div>
                                <table class="receipt-table">
                                    <tr>
                                        <td>Nama Peminjam</td>
                                        <td><strong><?= amankanTeks($strukTransaksi['nama_peminjam']) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Judul Buku</td>
                                        <td><strong><?= amankanTeks($strukTransaksi['judul_buku']) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Kode Katalog</td>
                                        <td><code><?= amankanTeks($strukTransaksi['kode_buku']) ?></code></td>
                                    </tr>
                                    <tr>
                                        <td>Lama Peminjaman</td>
                                        <td><?= $strukTransaksi['durasi_pinjam'] ?> hari (Batas: <?= $strukTransaksi['batas_standar'] ?> hari)</td>
                                    </tr>
                                    <tr>
                                        <td>Status Keterlambatan</td>
                                        <td>
                                            <span class="tag-status <?= $strukTransaksi['hari_terlambat'] > 0 ? 'tag-late' : 'tag-ready' ?>">
                                                <?= $strukTransaksi['rincian_waktu'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Denda Kotor (<?= $strukTransaksi['hari_terlambat'] ?> hari &times; <?= formatRupiah($strukTransaksi['tarif_per_hari']) ?>)</td>
                                        <td><?= formatRupiah($strukTransaksi['denda_dasar']) ?></td>
                                    </tr>
                                    <tr class="receipt-total">
                                        <td>Total Biaya Denda</td>
                                        <td style="color: #991b1b;"><?= formatRupiah($strukTransaksi['total_bayar']) ?></td>
                                    </tr>
                                </table>
                                <div class="field-hint" style="text-align: center; margin-top: 10px;">
                                    Batas maksimal denda buku adalah <?= formatRupiah(DENDA_MAKSIMAL) ?>.
                                </div>
                            </div>
                        <?php else: ?>
                            <div style="border: 1px dashed var(--line); border-radius: var(--radius); padding: 36px 20px; text-align: center; color: var(--ink-muted); background: #ffffff;">
                                <div style="font-size: 13.5px; font-weight: 600; color: var(--ink-primary); margin-bottom: 4px;">Formulir Pengembalian</div>
                                <p style="font-size: 12.5px;">Pilih buku pada formulir di sebelah kiri dan klik tombol untuk menghitung denda serta menerbitkan bukti.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Simulasi Menu Transaksi (DO-WHILE) -->
        <section class="panel">
            <div class="panel-header">
                <span class="panel-title">Menu Transaksi Sirkulasi</span>
            </div>
            <div class="panel-body">
                <form action="proses.php" method="POST" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end; margin-bottom: 14px;">
                    <input type="hidden" name="aksi" value="simulasi_dowhile">
                    <div style="flex: 1; min-width: 260px;">
                        <label class="field-label" for="menu_pilihan">Pilihan opsi layanan sirkulasi</label>
                        <select id="menu_pilihan" name="menu_pilihan" class="field-select">
                            <option value="1">Audit ketersediaan buku di rak</option>
                            <option value="2">Rekapitulasi total sanksi denda berjalan</option>
                            <option value="3">Daftar buku dalam peminjaman aktif</option>
                            <option value="0">Tutup sesi layanan sirkulasi</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary">
                        Jalankan Menu Layanan
                    </button>
                </form>

                <?php if ($hasilMenu): ?>
                    <div style="background: #ffffff; border: 1px solid var(--line); border-radius: var(--radius); padding: 18px 20px; margin-top: 14px; box-shadow: var(--shadow-sm);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid var(--line-subtle); padding-bottom: 8px;">
                            <strong style="font-size: 14.5px; color: var(--ink-primary);"><?= amankanTeks($hasilMenu['judul']) ?></strong>
                            <span class="tag-status <?= $hasilMenu['badge'] ?>"><?= amankanTeks($hasilMenu['status']) ?></span>
                        </div>
                        <p style="font-size: 13px; color: var(--ink-muted); margin-bottom: 12px;"><?= amankanTeks($hasilMenu['deskripsi']) ?></p>

                        <?php if (!empty($hasilMenu['data'])): ?>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                                <?php foreach ($hasilMenu['data'] as $label => $val): ?>
                                    <div style="background: var(--canvas); border: 1px solid var(--line-subtle); border-radius: var(--radius-sm); padding: 10px 14px;">
                                        <div style="font-size: 11px; font-weight: 600; color: var(--ink-muted); text-transform: uppercase;"><?= amankanTeks($label) ?></div>
                                        <div style="font-size: 18px; font-weight: 700; color: var(--ink-primary);"><?= amankanTeks($val) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($hasilMenu['list'])): ?>
                            <ul style="font-size: 13px; color: var(--ink-secondary); padding-left: 20px; margin-top: 8px;">
                                <?php foreach ($hasilMenu['list'] as $itemBuku): ?>
                                    <li style="margin-bottom: 4px;"><?= amankanTeks($itemBuku) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- 4. Antrean Pengembalian Masal -->
        <section class="panel">
            <div class="panel-header">
                <span class="panel-title">Pemrosesan Antrean Pengembalian Buku</span>
            </div>
            <div class="panel-body">
                <form action="proses.php" method="POST" style="margin-bottom: 14px;">
                    <input type="hidden" name="aksi" value="simulasi_antrean">
                    <button type="submit" class="btn btn-secondary">
                        Proses Batch Antrean Pengembalian
                    </button>
                </form>

                <?php if ($logAntreanWhile): ?>
                    <div class="table-wrap">
                        <table class="table-catalog">
                            <thead>
                                <tr>
                                    <th>Nomor Urut</th>
                                    <th>Kode Katalog</th>
                                    <th>Judul Buku</th>
                                    <th>Hari Terlambat</th>
                                    <th class="num-cell">Denda</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $urutanAntrean = 1;
                                foreach ($logAntreanWhile as $itemAntrean): 
                                ?>
                                    <tr>
                                        <td>Antrean <?= $urutanAntrean++ ?></td>
                                        <td><code><?= amankanTeks($itemAntrean['kode']) ?></code></td>
                                        <td><strong><?= amankanTeks($itemAntrean['judul']) ?></strong></td>
                                        <td><?= $itemAntrean['hari_terlambat'] ?> hari</td>
                                        <td class="num-cell"><strong style="color: #991b1b;"><?= formatRupiah($itemAntrean['denda']) ?></strong></td>
                                        <td>
                                            <span class="tag-status <?= $itemAntrean['denda'] > 0 ? 'tag-late' : 'tag-ready' ?>">
                                                <?= amankanTeks($itemAntrean['keterangan']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
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
