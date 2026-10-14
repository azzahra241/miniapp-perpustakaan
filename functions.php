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
    define('nama_perpustakaan', NAMA_PERPUSTAKAAN);
}
if (!defined('VERSI_APLIKASI')) {
    define('VERSI_APLIKASI', '1.0.0-UTS');
}

const DENDA_PER_HARI    = 1000;   // Biaya denda standar per hari keterlambatan (int)
const denda_per_hari    = DENDA_PER_HARI;
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
 */
function normalisasiJudul(string $judul): string
{
    $judulBersih = trim($judul);
    $judulSpasiTunggal = preg_replace('/\s+/', ' ', $judulBersih);
    return ucwords(strtolower($judulSpasiTunggal));
}

/**
 * [P4 - String Teknik 3] Format kode standar buku perpustakaan (contoh: BK007)
 */
function formatKodeBuku(int $nomor): string
{
    return 'BK' . str_pad((string) $nomor, 3, '0', STR_PAD_LEFT);
}

/**
 * [P4 - String Teknik 4] Pengamanan teks dari celah keamanan XSS
 */
function amankanTeks(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

/**
 * [P4 - String Teknik 5] Memformat angka ke dalam notasi mata uang Rupiah
 */
function formatRupiah(int|float $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * [P2, P3, P4] Menghitung denda berdasarkan hari keterlambatan
 */
function hitungDenda(int $hariTerlambat, int $tarif = DENDA_PER_HARI): int
{
    if ($hariTerlambat <= 0) {
        return 0;
    }
    $denda = $hariTerlambat * $tarif;
    return $denda > DENDA_MAKSIMAL ? DENDA_MAKSIMAL : $denda;
}

/**
 * [P4] Menentukan teks status peminjaman buku
 */
function tentukanStatusPinjam(bool $dipinjam, int $hariTerlambat, ?string $statusManual = null): string
{
    if ($statusManual !== null && $statusManual !== '') {
        $st = ucfirst(strtolower(trim($statusManual)));
        if (in_array($st, ['Tersedia', 'Dipinjam', 'Terlambat'])) {
            return $st;
        }
    }
    if (!$dipinjam) {
        return 'Tersedia';
    }
    return $hariTerlambat > 0 ? 'Terlambat' : 'Dipinjam';
}

/**
 * [P3 & P4] Menghitung konversi durasi keterlambatan ke satuan minggu dan hari
 */
function hitungKeterlambatan(int $hari): string
{
    if ($hari <= 0) {
        return 'Tepat Waktu (0 hari)';
    }
    $minggu = intdiv($hari, 7);
    $sisa   = $hari % 7;
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
 */
function cariBukuByKode(array $daftar, string $kode): ?array
{
    $kodeTarget = strtoupper(trim($kode));
    $i          = 0;
    $jumlahData = count($daftar);
    $hasil      = null;

    while ($i < $jumlahData) {
        $kodeBuku = strtoupper($daftar[$i]['kode'] ?? $daftar[$i]['kodeBuku'] ?? '');
        if ($kodeBuku === $kodeTarget) {
            $hasil = $daftar[$i];
            break;
        }
        $i++;
    }
    return $hasil;
}

/**
 * [P5 - WHILE 2 & P4 - String Teknik 6] Mencari buku berdasarkan keyword judul
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

    while ($i < $jumlahData) {
        $judulBuku = strtolower($daftar[$i]['judul'] ?? '');
        if (str_contains($judulBuku, $keyword)) {
            $hasil[] = $daftar[$i];
        }
        $i++;
    }
    return $hasil;
}

/**
 * [P5 - WHILE 3] Simulasi pemrosesan antrean pengembalian buku
 */
function prosesAntreanPengembalian(array $antrean): array
{
    $logProses = [];
    $antreanSalinan = $antrean;

    while (!empty($antreanSalinan)) {
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
 * [P6 - DO-WHILE] Eksekusi menu transaksi terstruktur
 */
function eksekusiMenuSirkulasi(array $daftar, int $pilihan): array
{
    $hasil = [];
    $iterasi = 0;

    do {
        if ($pilihan === 1) {
            $jumlahTersedia = hitungJumlahTersedia($daftar);
            $totalBuku = count($daftar);
            $hasil = [
                'judul'      => 'Hasil Audit Ketersediaan Rak',
                'deskripsi'  => 'Status ketersediaan buku fisik yang siap dipinjam:',
                'data'       => [
                    'Buku Siap Pinjam'   => $jumlahTersedia . ' Judul',
                    'Buku Dalam Pinjaman'=> ($totalBuku - $jumlahTersedia) . ' Judul',
                    'Total Katalog'      => $totalBuku . ' Judul',
                ],
                'status'     => 'Katalog siap melayani peminjaman anggota perpustakaan.',
                'badge'      => 'tag-ready'
            ];
        } elseif ($pilihan === 2) {
            $totalDenda = hitungTotalDenda($daftar);
            $hasil = [
                'judul'      => 'Rekapitulasi Sanksi Keterlambatan',
                'deskripsi'  => 'Ringkasan akumulasi denda sirkulasi yang belum diselesaikan:',
                'data'       => [
                    'Total Akumulasi Denda' => formatRupiah($totalDenda),
                    'Tarif Denda Harian'    => formatRupiah(DENDA_PER_HARI) . ' / hari',
                    'Plafon Maksimal Buku'  => formatRupiah(DENDA_MAKSIMAL),
                ],
                'status'     => $totalDenda > 0 ? 'Terdapat sanksi denda aktif dalam sirkulasi.' : 'Tidak ada tunggakan denda keterlambatan.',
                'badge'      => $totalDenda > 0 ? 'tag-late' : 'tag-ready'
            ];
        } elseif ($pilihan === 3) {
            $dipinjamList = [];
            foreach ($daftar as $item) {
                $st = ucfirst(strtolower(trim($item['status'] ?? '')));
                if ($st !== 'Tersedia') {
                    $dipinjamList[] = ($item['kode'] ?? '') . ' - ' . ($item['judul'] ?? '') . ' (' . $st . ')';
                }
            }
            $hasil = [
                'judul'      => 'Verifikasi Sirkulasi Aktif',
                'deskripsi'  => 'Daftar koleksi buku yang saat ini tercatat dalam peminjaman:',
                'list'       => $dipinjamList,
                'status'     => count($dipinjamList) . ' transaksi sirkulasi aktif telah diverifikasi.',
                'badge'      => 'tag-borrowed'
            ];
        } else {
            $hasil = [
                'judul'      => 'Penutupan Sesi Layanan',
                'deskripsi'  => 'Sesi transaksi sirkulasi perpustakaan telah ditutup dengan aman.',
                'status'     => 'Sistem kembali ke status siaga.',
                'badge'      => 'tag-neutral'
            ];
        }
        $iterasi++;
    } while ($iterasi < 1); // Minimal 1 kali eksekusi

    return $hasil;
}

/**
 * Kompatibilitas fungsi simulasiMenuTransaksi lama
 */
function simulasiMenuTransaksi(array $daftar, array $inputSimulasi): array
{
    $log    = [];
    $urutan = 0;

    do {
        $pilihan = $inputSimulasi[$urutan] ?? 0;
        if ($pilihan === 1) {
            $log[] = 'Audit Ketersediaan: Terdapat ' . hitungJumlahTersedia($daftar) . ' judul buku siap dipinjam.';
        } elseif ($pilihan === 2) {
            $log[] = 'Rekap Denda: Total akumulasi denda adalah ' . formatRupiah(hitungTotalDenda($daftar)) . '.';
        } elseif ($pilihan === 3) {
            $log[] = 'Verifikasi Sirkulasi: Seluruh status buku aktif telah disinkronkan.';
        } elseif ($pilihan === 0) {
            $log[] = 'Selesai: Sesi transaksi sirkulasi berhasil ditutup.';
        } else {
            $log[] = "Pilihan {$pilihan} tidak dikenali.";
        }
        $urutan++;
    } while ($pilihan !== 0 && $urutan < count($inputSimulasi));

    return $log;
}

// =========================================================================
// [P7] IMPLEMENTASI FOR & PENGOLAHAN AGREGAT DATA KOLEKSI
// =========================================================================

/**
 * Menghitung total seluruh denda dalam koleksi buku
 */
function hitungTotalDenda(array $daftar): int
{
    $total = 0;
    foreach ($daftar as $buku) {
        $st = ucfirst(strtolower(trim($buku['status'] ?? '')));
        if ($st === 'Tersedia') {
            continue; // Buku berstatus Tersedia tidak dikenakan denda
        }
        $hari = (int) ($buku['hari_terlambat'] ?? ($buku['hariTerlambat'] ?? 0));
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
    foreach ($daftar as $buku) {
        $st = ucfirst(strtolower(trim($buku['status'] ?? '')));
        if ($st === 'Tersedia') {
            $jumlahTersedia++;
        } elseif ($st === '' && empty($buku['dipinjam'])) {
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
    for ($i = 0; $i < $total; $i++) {
        $objekBuku[] = Buku::fromArray($dataArray[$i]);
    }
    return $objekBuku;
}
?>