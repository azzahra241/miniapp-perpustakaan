<?php
/**
 * Data Koleksi Perpustakaan Pustaka Cendekia
 * [P3] Variabel, Array & Tipe Data
 * [P7] Koleksi Data Inventaris
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
        'status'         => 'Tersedia', // Dapat diubah ke 'Tersedia', 'Dipinjam', atau 'Terlambat'
        'hari_terlambat' => 3,
        'stok'           => 3,
    ],
    [
        'kode'           => 'BK002',
        'judul'          => 'Pemrograman Berorientasi Objek dengan PHP',
        'pengarang'      => 'Ari Wibowo, M.Kom',
        'tahun'          => 2024,
        'rating'         => 4.9,
        'kategori'       => 'Teknologi & Komputer',
        'status'         => 'Tersedia',
        'hari_terlambat' => 0,
        'stok'           => 4,
    ],
    [
        'kode'           => 'BK003',
        'judul'          => 'Struktur Data dan Algoritma',
        'pengarang'      => 'Rinaldi Munir',
        'tahun'          => 2021,
        'rating'         => 4.7,
        'kategori'       => 'Ilmu Komputer',
        'status'         => 'Terlambat',
        'hari_terlambat' => 5,
        'stok'           => 2,
    ],
    [
        'kode'           => 'BK004',
        'judul'          => 'Basis Data Relational & SQL Terapan',
        'pengarang'      => 'Abdul Kadir',
        'tahun'          => 2020,
        'rating'         => 4.5,
        'kategori'       => 'Basis Data',
        'status'         => 'Tersedia',
        'hari_terlambat' => 0,
        'stok'           => 5,
    ],
    [
        'kode'           => 'BK005',
        'judul'          => 'Pengantar Kecerdasan Buatan & Machine Learning',
        'pengarang'      => 'Suyanto',
        'tahun'          => 2022,
        'rating'         => 4.6,
        'kategori'       => 'Kecerdasan Buatan',
        'status'         => 'Dipinjam',
        'hari_terlambat' => 0,
        'stok'           => 2,
    ],
    [
        'kode'           => 'BK006',
        'judul'          => 'Home Sweet Loan',
        'pengarang'      => 'Almira Bastari',
        'tahun'          => 2022,
        'rating'         => 4.3,
        'kategori'       => 'Sastra & Fiksi',
        'status'         => 'Tersedia',
        'hari_terlambat' => 0,
        'stok'           => 3,
    ],
    [
        'kode'           => 'BK007',
        'judul'          => 'Laut Bercerita',
        'pengarang'      => 'Leila S. Chudori',
        'tahun'          => 2017,
        'rating'         => 4.8,
        'kategori'       => 'Sastra & Fiksi',
        'status'         => 'Terlambat',
        'hari_terlambat' => 4,
        'stok'           => 1,
    ],
    [
        'kode'           => 'BK008',
        'judul'          => 'Bumi Manusia',
        'pengarang'      => 'Pramoedya Ananta Toer',
        'tahun'          => 1980,
        'rating'         => 4.9,
        'kategori'       => 'Sejarah & Sastra',
        'status'         => 'Dipinjam',
        'hari_terlambat' => 0,
        'stok'           => 3,
    ],
    [
        'kode'           => 'BK009',
        'judul'          => 'The Midnight Library',
        'pengarang'      => 'Matt Haig',
        'tahun'          => 2020,
        'rating'         => 4.7,
        'kategori'       => 'Novel Terjemahan',
        'status'         => 'Terlambat',
        'hari_terlambat' => 10,
        'stok'           => 2,
    ],
    [
        'kode'           => 'BK010',
        'judul'          => 'Before the Coffee Gets Cold',
        'pengarang'      => 'Toshikazu Kawaguchi',
        'tahun'          => 2015,
        'rating'         => 3.9,
        'kategori'       => 'Novel Terjemahan',
        'status'         => 'Terlambat',
        'hari_terlambat' => 55,
        'stok'           => 1,
    ],
    [
        'kode'           => 'BK011',
        'judul'          => 'Laskar Pelangi',
        'pengarang'      => 'Andrea Hirata',
        'tahun'          => 2005,
        'rating'         => 4.6,
        'kategori'       => 'Inspiratif & Edukasi',
        'status'         => 'Tersedia',
        'hari_terlambat' => 0,
        'stok'           => 4,
    ],
    [
        'kode'           => 'BK012',
        'judul'          => 'Filosofi Teras: Hidup Tenang dengan Stoisisme',
        'pengarang'      => 'Henry Manampiring',
        'tahun'          => 2019,
        'rating'         => 4.8,
        'kategori'       => 'Pengembangan Diri',
        'status'         => 'Tersedia',
        'hari_terlambat' => 0,
        'stok'           => 5,
    ]
];

// Sinkronisasi otomatis: memastikan status yang diubah langsung berdampak ke seluruh sistem
foreach ($koleksiBuku as &$item) {
    $st = ucfirst(strtolower(trim($item['status'] ?? 'Tersedia')));
    if ($st === 'Tersedia') {
        $item['status']         = 'Tersedia';
        $item['dipinjam']       = false;
        $item['hari_terlambat'] = 0;
    } elseif ($st === 'Dipinjam') {
        $item['status']         = 'Dipinjam';
        $item['dipinjam']       = true;
        $item['hari_terlambat'] = 0;
    } elseif ($st === 'Terlambat') {
        $item['status']         = 'Terlambat';
        $item['dipinjam']       = true;
        if (($item['hari_terlambat'] ?? 0) <= 0) {
            $item['hari_terlambat'] = 1;
        }
    }
}
unset($item);

// Inisialisasi variabel $daftarBuku berisi Array Objek Buku
$daftarBuku = [];
foreach ($koleksiBuku as $b) {
    $daftarBuku[] = Buku::fromArray($b);
}

// Mengembalikan array data untuk pemanggilan require
return $koleksiBuku;
?>