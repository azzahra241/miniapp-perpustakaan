<?php

define('nama_perpustakaan', 'Pustaka Cendekia');
const denda_per_hari    = 1000;   
const denda_maksimal    = 50000;  
const batas_pinjam_hari = 7;      

function normalisasiJudul(string $judul): string
{
    $judul = trim($judul);
    $judul = preg_replace('/\s+/', ' ', $judul); 
    return ucwords(strtolower($judul));
}

function formatKodeBuku(int $nomor): string
{
    return 'BK' . str_pad((string) $nomor, 3, '0', STR_PAD_LEFT);
}

function amankanTeks(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

function formatRupiah(int|float $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function hitungDenda(int $hariTerlambat, int $tarif = denda_per_hari): int
{
    if ($hariTerlambat <= 0) {
        return 0;
    }
    $denda = $hariTerlambat * $tarif;
    return $denda > denda_maksimal ? denda_maksimal : $denda;
}

function tentukanStatusPinjam(bool $dipinjam, int $hariTerlambat): string
{
    if (!$dipinjam) {
        return 'Tersedia';
    }
    return $hariTerlambat > 0 ? 'Terlambat' : 'Dipinjam';
}

function hitungKeterlambatan(int $hari): string
{
    $minggu = intdiv($hari, 7);
    $sisa   = $hari % 7;
    return "$minggu minggu $sisa hari";
}

function ambilHariTerlambat(mixed $input): int
{
    return is_numeric($input) ? max(0, (int) $input) : 0;
}

function cariBukuByKode(array $daftar, string $kode): ?array
{
    $kode  = strtoupper(trim($kode));
    $i     = 0;
    $hasil = null;

    while ($i < count($daftar)) {
        if ($daftar[$i]['kode'] === $kode) {
            $hasil = $daftar[$i];
            break;
        }
        $i++;
    }
    return $hasil;
}

function cariBukuByJudul(array $daftar, string $keyword): array
{
    $keyword = strtolower(trim($keyword));
    $hasil   = [];
    if ($keyword === '') {
        return $hasil;
    }

    $i = 0;
    while ($i < count($daftar)) {
        if (str_contains(strtolower($daftar[$i]['judul']), $keyword)) {
            $hasil[] = $daftar[$i];
        }
        $i++;
    }
    return $hasil;
}

function hitungTotalDenda(array $daftar): int
{
    $total = 0;
    $i     = 0;
    while ($i < count($daftar)) {
        $total += hitungDenda($daftar[$i]['hari_terlambat']);
        $i++;
    }
    return $total;
}

function hitungJumlahTersedia(array $daftar): int
{
    $jumlah = 0;
    $i      = 0;
    while ($i < count($daftar)) {
        if (!$daftar[$i]['dipinjam']) {
            $jumlah++;
        }
        $i++;
    }
    return $jumlah;
}

function simulasiMenuTransaksi(array $daftar, array $inputSimulasi): array
{
    $log    = [];
    $urutan = 0;

    do {
        $pilihan = $inputSimulasi[$urutan] ?? 0;

        if ($pilihan === 1) {
            $log[] = 'Menu 1 - Cek ketersediaan: ' . hitungJumlahTersedia($daftar) . ' buku tersedia';
        } elseif ($pilihan === 2) {
            $log[] = 'Menu 2 - Total denda: ' . formatRupiah(hitungTotalDenda($daftar));
        } elseif ($pilihan === 0) {
            $log[] = 'Menu 0 - Keluar dari menu transaksi';
        } else {
            $log[] = "Pilihan $pilihan tidak dikenal";
        }

        $urutan++;
    } while ($pilihan !== 0);

    return $log;
}
?>