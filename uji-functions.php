<?php
require_once __DIR__ . '/functions.php';
$buku = require __DIR__ . '/data/sample.php';

echo '<h2>' . nama_perpustakaan . ' - Uji Function</h2>';

echo '<h3>1. Status dan Denda</h3>';
foreach ($buku as $b) {
    $status = tentukanStatusPinjam($b['dipinjam'], $b['hari_terlambat']);
    $denda  = hitungDenda($b['hari_terlambat']);
    echo $b['kode'] . ' | ' . amankanTeks(normalisasiJudul($b['judul']))
       . ' | ' . $status
       . ' | Denda: ' . formatRupiah($denda)
       . ' | Terlambat: ' . hitungKeterlambatan($b['hari_terlambat']) . '<br>';
}

echo '<h3>2. Pencarian Buku (WHILE)</h3>';
$ketemu = cariBukuByKode($buku, 'bk004');
echo 'Cari kode "bk004": ' . ($ketemu ? normalisasiJudul($ketemu['judul']) : 'Tidak ditemukan') . '<br>';

$hasilJudul = cariBukuByJudul($buku, 'pemrograman');
echo 'Cari judul "pemrograman": ' . count($hasilJudul) . ' buku ditemukan<br>';

echo '<h3>3. Rekap</h3>';
echo 'Total denda: ' . formatRupiah(hitungTotalDenda($buku)) . '<br>';
echo 'Buku tersedia: ' . hitungJumlahTersedia($buku) . '<br>';

echo '<h3>4. Konversi Input</h3>';
echo 'Input "7" menjadi: ' . ambilHariTerlambat('7') . '<br>';
echo 'Input "abc" menjadi: ' . ambilHariTerlambat('abc') . '<br>';
echo 'Format kode nomor 12: ' . formatKodeBuku(12) . '<br>';

echo '<h3>5. Simulasi Menu Transaksi (DO-WHILE)</h3>';
foreach (simulasiMenuTransaksi($buku, [1, 2, 0]) as $baris) {
    echo $baris . '<br>';
}
?>