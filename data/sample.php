<?php
/**
 * Data Koleksi Perpustakaan Pustaka Cendekia
 * Mengintegrasikan data dari miniapp-perpustakaan dan TUGAS_UTS.
 * Memenuhi Peta Kompetensi Pertemuan 3 (P3: Array & Tipe Data) dan P7 (Data Koleksi).
 */

require_once __DIR__ . '/../classes/Buku.php';

$koleksiBuku = [
    [
        'kode'           => 'BK001',
        'judul'          => 'Belajar Pemrograman PHP Modern',
        'pengarang'      => 'Bimo Sunarfrihanto',
        'tahun'          => 2023,
        'rating'         => 4.8,
        'kategori'       => 'Teknologi & Komputer',
        'dipinjam'       => true,
        'hari_terlambat' => 3,
        'status'         => 'Terlambat',
        'stok'           => 3,
    ],
    [
        'kode'           => 'BK002',
        'judul'          => 'Pemrograman Berorientasi Objek dengan PHP',
        'pengarang'      => 'Ari Wibowo, M.Kom',
        'tahun'          => 2024,
        'rating'         => 4.9,
        'kategori'       => 'Teknologi & Komputer',
        'dipinjam'       => false,
        'hari_terlambat' => 0,
        'status'         => 'Tersedia',
        'stok'           => 4,
    ],
    [
        'kode'           => 'BK003',
        'judul'          => 'Struktur Data dan Algoritma',
        'pengarang'      => 'Rinaldi Munir',
        'tahun'          => 2021,
        'rating'         => 4.7,
        'kategori'       => 'Ilmu Komputer',
        'dipinjam'       => true,
        'hari_terlambat' => 5,
        'status'         => 'Terlambat',
        'stok'           => 2,
    ],
    [
        'kode'           => 'BK004',
        'judul'          => 'Basis Data Relational & SQL Terapan',
        'pengarang'      => 'Abdul Kadir',
        'tahun'          => 2020,
        'rating'         => 4.5,
        'kategori'       => 'Basis Data',
        'dipinjam'       => false,
        'hari_terlambat' => 0,
        'status'         => 'Tersedia',
        'stok'           => 5,
    ],
    [
        'kode'           => 'BK005',
        'judul'          => 'Pengantar Kecerdasan Buatan & Machine Learning',
        'pengarang'      => 'Suyanto',
        'tahun'          => 2022,
        'rating'         => 4.6,
        'kategori'       => 'Kecerdasan Buatan',
        'dipinjam'       => true,
        'hari_terlambat' => 0,
        'status'         => 'Dipinjam',
        'stok'           => 2,
    ],
    [
        'kode'           => 'BK006',
        'judul'          => 'Home Sweet Loan',
        'pengarang'      => 'Almira Bastari',
        'tahun'          => 2022,
        'rating'         => 4.3,
        'kategori'       => 'Sastra & Fiksi',
        'dipinjam'       => false,
        'hari_terlambat' => 0,
        'status'         => 'Tersedia',
        'stok'           => 3,
    ],
    [
        'kode'           => 'BK007',
        'judul'          => 'Laut Bercerita',
        'pengarang'      => 'Leila S. Chudori',
        'tahun'          => 2017,
        'rating'         => 4.8,
        'kategori'       => 'Sastra & Fiksi',
        'dipinjam'       => true,
        'hari_terlambat' => 4,
        'status'         => 'Terlambat',
        'stok'           => 1,
    ],
    [
        'kode'           => 'BK008',
        'judul'          => 'Bumi Manusia',
        'pengarang'      => 'Pramoedya Ananta Toer',
        'tahun'          => 1980,
        'rating'         => 4.9,
        'kategori'       => 'Sejarah & Sastra',
        'dipinjam'       => true,
        'hari_terlambat' => 0,
        'status'         => 'Dipinjam',
        'stok'           => 3,
    ],
    [
        'kode'           => 'BK009',
        'judul'          => 'The Midnight Library',
        'pengarang'      => 'Matt Haig',
        'tahun'          => 2020,
        'rating'         => 4.7,
        'kategori'       => 'Novel Terjemahan',
        'dipinjam'       => true,
        'hari_terlambat' => 10,
        'status'         => 'Terlambat',
        'stok'           => 2,
    ],
    [
        'kode'           => 'BK010',
        'judul'          => 'Before the Coffee Gets Cold',
        'pengarang'      => 'Toshikazu Kawaguchi',
        'tahun'          => 2015,
        'rating'         => 3.9,
        'kategori'       => 'Novel Terjemahan',
        'dipinjam'       => true,
        'hari_terlambat' => 55,
        'status'         => 'Terlambat',
        'stok'           => 1,
    ],
    [
        'kode'           => 'BK011',
        'judul'          => 'Laskar Pelangi',
        'pengarang'      => 'Andrea Hirata',
        'tahun'          => 2005,
        'rating'         => 4.6,
        'kategori'       => 'Inspiratif & Edukasi',
        'dipinjam'       => false,
        'hari_terlambat' => 0,
        'status'         => 'Tersedia',
        'stok'           => 4,
    ],
    [
        'kode'           => 'BK012',
        'judul'          => 'Filosofi Teras: Hidup Tenang dengan Stoisisme',
        'pengarang'      => 'Henry Manampiring',
        'tahun'          => 2019,
        'rating'         => 4.8,
        'kategori'       => 'Pengembangan Diri',
        'dipinjam'       => false,
        'hari_terlambat' => 0,
        'status'         => 'Tersedia',
        'stok'           => 5,
    ]
];

// Inisialisasi variabel $daftarBuku berisi Array Objek Buku
// Menjaga kompatibilitas 100% dengan Laporan.php dari TUGAS_UTS
$daftarBuku = [];
foreach ($koleksiBuku as $b) {
    $daftarBuku[] = Buku::fromArray($b);
}

// Mengembalikan array data untuk pemanggilan require
return $koleksiBuku;
?>