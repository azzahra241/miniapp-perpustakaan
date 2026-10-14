<?php
/**
 * Modul Fungsi Prosedural & Konfigurasi Sistem Perpustakaan
 * Memenuhi Peta Kompetensi Pertemuan 1–7:
 * - [P1] Paradigma Pemrograman PHP & Sintaks Dasar
 * - [P2] Fungsi Prosedural
 * - [P3] Variabel, Scope, Konstanta, 5 Tipe Data, Type Casting
 * - [P4] Operator Lengkap (Aritmatika, Perbandingan, Logika, Ternary) & Manipulasi String
 * - [P5] Kontrol Perulangan WHILE
 * - [P6] Kontrol Perulangan DO-WHILE
 * - [P7] Kontrol Perulangan FOR & Pengolahan Array
 */

// Memuat Class Buku untuk integrasi OOP [P2]
require_once __DIR__ . '/classes/Buku.php';

// =========================================================================
// [P3] KONSTANTA SISTEM (Menggunakan define() dan const)
// =========================================================================
if (!defined('NAMA_PERPUSTAKAAN')) {
    define('NAMA_PERPUSTAKAAN', 'Pustaka Cendekia');
}
if (!defined('nama_perpustakaan')) {
    define('nama_perpustakaan', NAMA_PERPUSTAKAAN); // Kompatibilitas kode lama
}
if (!defined('VERSI_APLIKASI')) {
    define('VERSI_APLIKASI', '1.0.0-UTS');
}

const DENDA_PER_HARI    = 1000;   // Biaya denda standar per hari keterlambatan (int)
const denda_per_hari    = DENDA_PER_HARI; // Kompatibilitas kode lama
const DENDA_MAKSIMAL    = 50000;  // Batas plafon denda maksimal per buku (int)
const denda_maksimal    = DENDA_MAKSIMAL;
const BATAS_PINJAM_HARI = 7;      // Masa peminjaman standar dalam hari (int)
const batas_pinjam_hari = BATAS_PINJAM_HARI;
const TARIF_ADMIN_PROSES = 2500.50; // Biaya administrasi perpanjangan (float)

// =========================================================================
// [P2 & P4] FUNGSI PROSEDURAL & MANIPULASI STRING
// =========================================================================

/**
 * [P4 - String Teknik 1 & 2] Membersihkan dan menormalisasi format teks judul
 * Menggunakan trim(), preg_replace(), strtolower(), ucwords()
 */
function normalisasiJudul(string $judul): string
{
    $judulBersih = trim($judul);
    $judulSpasiTunggal = preg_replace('/\s+/', ' ', $judulBersih);
    return ucwords(strtolower($judulSpasiTunggal));
}

/**
 * [P4 - String Teknik 3] Format kode standar buku perpustakaan (contoh: BK007)
 * Menggunakan str_pad() dan casting string
 */
function formatKodeBuku(int $nomor): string
{
    return 'BK' . str_pad((string) $nomor, 3, '0', STR_PAD_LEFT);
}

/**
 * [P4 - String Teknik 4] Pengamanan teks dari celah keamanan XSS (Cross Site Scripting)
 * Menggunakan htmlspecialchars()
 */
function amankanTeks(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

/**
 * [P4 - String Teknik 5] Memformat angka ke dalam notasi mata uang Rupiah
 * Menggunakan number_format()
 */
function formatRupiah(int|float $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * [P2, P3, P4] Menghitung denda berdasarkan hari keterlambatan
 * Menggunakan operator perbandingan, aritmatika, dan ternary
 */
function hitungDenda(int $hariTerlambat, int $tarif = DENDA_PER_HARI): int
{
    if ($hariTerlambat <= 0) {
        return 0;
    }
    // [P4] Operator Aritmatika (*)
    $denda = $hariTerlambat * $tarif;
    // [P4] Operator Ternary (? :) dan Perbandingan (>)
    return $denda > DENDA_MAKSIMAL ? DENDA_MAKSIMAL : $denda;
}

/**
 * [P4] Menentukan teks status peminjaman buku
 * Menggunakan operator logika (!), perbandingan (>), dan ternary
 */
function tentukanStatusPinjam(bool $dipinjam, int $hariTerlambat): string
{
    if (!$dipinjam) {
        return 'Tersedia';
    }
    return $hariTerlambat > 0 ? 'Terlambat' : 'Dipinjam';
}

/**
 * [P3 & P4] Menghitung konversi durasi keterlambatan ke satuan minggu dan hari
 * Menggunakan fungsi aritmatika intdiv() dan modulo (%)
 */
function hitungKeterlambatan(int $hari): string
{
    if ($hari <= 0) {
        return 'Tepat Waktu (0 hari)';
    }
    $minggu = intdiv($hari, 7); // Pembagian bilangan bulat
    $sisa   = $hari % 7;        // Sisa bagi hari
    return "{$minggu} minggu {$sisa} hari ({$hari} hari)";
}

/**
 * [P3] Konversi input dari form (type casting eksplisit dan sanitasi)
 */
function ambilHariTerlambat(mixed $input): int
{
    return is_numeric($input) ? max(0, (int) $input) : 0;
}

// =========================================================================
// [P5] IMPLEMENTASI PERULANGAN WHILE (Loop Berbasis Kondisi)
// =========================================================================

/**
 * [P5 - WHILE 1] Mencari buku dalam daftar array berdasarkan kode buku
 * Perulangan berhenti saat buku ditemukan atau mencapai akhir array
 */
function cariBukuByKode(array $daftar, string $kode): ?array
{
    $kodeTarget = strtoupper(trim($kode));
    $i          = 0;
    $jumlahData = count($daftar);
    $hasil      = null;

    // [P5] While loop mencari hingga target ditemukan
    while ($i < $jumlahData) {
        $kodeBuku = strtoupper($daftar[$i]['kode'] ?? $daftar[$i]['kodeBuku'] ?? '');
        if ($kodeBuku === $kodeTarget) {
            $hasil = $daftar[$i];
            break; // Hentikan loop segera setelah ditemukan
        }
        $i++;
    }
    return $hasil;
}

/**
 * [P5 - WHILE 2 & P4 - String Teknik 6] Mencari buku berdasarkan keyword judul
 * Menggunakan while loop dan fungsi string str_contains / stripos
 */
function cariBukuByJudul(array $daftar, string $keyword): array
{
    $keyword = strtolower(trim($keyword));
    $hasil   = [];
    if ($keyword === '') {
        return $hasil;
    }

    $i          = 0;
    $jumlahData = count($daftar);

    // [P5] While loop iterasi data array
    while ($i < $jumlahData) {
        $judulBuku = strtolower($daftar[$i]['judul'] ?? '');
        // [P4] Teknik manipulasi string str_contains
        if (str_contains($judulBuku, $keyword)) {
            $hasil[] = $daftar[$i];
        }
        $i++;
    }
    return $hasil;
}

/**
 * [P5 - WHILE 3] Simulasi pemrosesan antrean pengembalian buku (Queue Processing)
 * Menjalankan proses antrean selama kondisi array antrean masih memiliki elemen
 */
function prosesAntreanPengembalian(array $antrean): array
{
    $logProses = [];
    $antreanSalinan = $antrean; // Duplikasi array antrean

    while (!empty($antreanSalinan)) {
        // Ambil elemen pertama antrean
        $item = array_shift($antreanSalinan);
        $denda = hitungDenda((int)($item['hari_terlambat'] ?? 0));
        $logProses[] = [
            'kode'           => $item['kode'],
            'judul'          => $item['judul'],
            'hari_terlambat' => $item['hari_terlambat'],
            'denda'          => $denda,
            'keterangan'     => $denda > 0 ? 'Dikenakan denda ' . formatRupiah($denda) : 'Bebas denda (Tepat Waktu)'
        ];
    }
    return $logProses;
}

// =========================================================================
// [P6] IMPLEMENTASI PERULANGAN DO-WHILE (Minimal Satu Kali Eksekusi)
// =========================================================================

/**
 * [P6 - DO-WHILE] Simulasi eksekusi siklus menu transaksi perpustakaan
 * Struktur DO-WHILE menjamin minimal satu siklus menu dieksekusi sebelum evaluasi terminasi
 */
function simulasiMenuTransaksi(array $daftar, array $inputSimulasi): array
{
    $log    = [];
    $urutan = 0;

    do {
        $pilihan = $inputSimulasi[$urutan] ?? 0;

        if ($pilihan === 1) {
            $jumlahTersedia = hitungJumlahTersedia($daftar);
            $log[] = "[Menu 1] Audit Ketersediaan: Terdapat {$jumlahTersedia} judul buku siap dipinjam.";
        } elseif ($pilihan === 2) {
            $totalDenda = hitungTotalDenda($daftar);
            $log[] = '[Menu 2] Rekap Denda: Total akumulasi denda berjalan adalah ' . formatRupiah($totalDenda) . '.';
        } elseif ($pilihan === 3) {
            $log[] = '[Menu 3] Verifikasi Sirkulasi: Seluruh status buku aktif telah disinkronkan.';
        } elseif ($pilihan === 0) {
            $log[] = '[Menu 0] Selesai: Sesi menu transaksi berhasil ditutup secara aman.';
        } else {
            $log[] = "[Menu Invalid] Pilihan {$pilihan} tidak dikenali oleh sistem.";
        }

        $urutan++;
    } while ($pilihan !== 0 && $urutan < count($inputSimulasi));

    return $log;
}

// =========================================================================
// [P7] IMPLEMENTASI FOR & PENGOLAHAN AGREGAT DATA KOLEKSI
// =========================================================================

/**
 * Menghitung total seluruh denda dalam koleksi buku menggunakan while / for
 */
function hitungTotalDenda(array $daftar): int
{
    $total = 0;
    $jumlah = count($daftar);
    for ($i = 0; $i < $jumlah; $i++) {
        $hari = (int) ($daftar[$i]['hari_terlambat'] ?? ($daftar[$i]['hariTerlambat'] ?? 0));
        $total += hitungDenda($hari);
    }
    return $total;
}

/**
 * Menghitung total judul buku yang berstatus tersedia
 */
function hitungJumlahTersedia(array $daftar): int
{
    $jumlahTersedia = 0;
    $total = count($daftar);
    for ($i = 0; $i < $total; $i++) {
        $isDipinjam = false;
        if (isset($daftar[$i]['dipinjam'])) {
            $isDipinjam = (bool) $daftar[$i]['dipinjam'];
        } elseif (isset($daftar[$i]['status'])) {
            $isDipinjam = strtolower($daftar[$i]['status']) !== 'tersedia';
        }
        if (!$isDipinjam) {
            $jumlahTersedia++;
        }
    }
    return $jumlahTersedia;
}

/**
 * Memuat koleksi data buku sebagai array objek Buku [P2: OOP]
 */
function muatObjekBuku(array $dataArray): array
{
    $objekBuku = [];
    $total = count($dataArray);
    // [P7] Perulangan FOR untuk membentuk array objek
    for ($i = 0; $i < $total; $i++) {
        $objekBuku[] = Buku::fromArray($dataArray[$i]);
    }
    return $objekBuku;
}
?>