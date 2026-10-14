<?php
/**
 * Class Buku - Pemrograman Berorientasi Objek (OOP)
 * Memenuhi Peta Kompetensi Pertemuan 2 (P2: Procedural & OOP)
 * dan Pertemuan 3 (P3: Variabel, Tipe Data, Scope).
 * 
 * Modul ini merepresentasikan entitas Buku dalam Sistem Perpustakaan "Pustaka Cendekia".
 */

class Buku
{
    // [P3] Variabel dan Tipe Data Properti (String, Int, Float, Bool)
    public string $kode;
    public string $kodeBuku; // Alias untuk kompatibilitas penuh dengan TUGAS_UTS
    public string $judul;
    public string $pengarang;
    public int $tahun;
    public float $rating;
    public bool $dipinjam;
    public string $status;
    public int $hariTerlambat;
    public string $kategori;

    /**
     * [P2] Constructor untuk inisialisasi Objek Buku
     * Fleksibel menerima format parameter TUGAS_UTS maupun miniapp-perpustakaan
     */
    public function __construct(
        string $kode,
        string $judul,
        string|bool $statusAtauDipinjam = false,
        int $hariTerlambat = 0,
        string $pengarang = 'Penulis Tidak Diketahui',
        int $tahun = 2024,
        float $rating = 4.5,
        string $kategori = 'Umum'
    ) {
        $this->kode         = $kode;
        $this->kodeBuku     = $kode; // Sinkronisasi properti
        $this->judul        = $judul;
        $this->hariTerlambat= max(0, $hariTerlambat);
        $this->pengarang    = $pengarang;
        $this->tahun        = $tahun;
        $this->rating       = $rating;
        $this->kategori     = $kategori;

        // [P4] Operator Logika & Ternary untuk menentukan status & boolean pinjam
        if (is_bool($statusAtauDipinjam)) {
            $this->dipinjam = $statusAtauDipinjam;
            if (!$this->dipinjam) {
                $this->status = 'Tersedia';
            } else {
                $this->status = $this->hariTerlambat > 0 ? 'Terlambat' : 'Dipinjam';
            }
        } else {
            $this->status = (string) $statusAtauDipinjam;
            $statusLower  = strtolower($this->status);
            $this->dipinjam = ($statusLower === 'dipinjam' || $statusLower === 'terlambat');
        }
    }

    /**
     * [P2] Method menghitung denda keterlambatan buku
     * [P4] Menggunakan operator aritmatika dan ternary
     */
    public function hitungDenda(int $tarif = 1000, int $maksimal = 50000): int
    {
        if ($this->hariTerlambat <= 0 || !$this->dipinjam) {
            return 0;
        }
        $total = $this->hariTerlambat * $tarif;
        return $total > $maksimal ? $maksimal : $total;
    }

    /**
     * Mengecek apakah buku saat ini berstatus terlambat
     */
    public function isTerlambat(): bool
    {
        return $this->dipinjam && $this->hariTerlambat > 0;
    }

    /**
     * Mengambil status buku yang terstandarisasi
     */
    public function getStatusPinjam(): string
    {
        return $this->status;
    }

    /**
     * [P4] Format nominal denda dalam format mata uang Rupiah
     */
    public function formatRupiahDenda(): string
    {
        return 'Rp ' . number_format($this->hitungDenda(), 0, ',', '.');
    }

    /**
     * [P4] Representasi visual rating buku dalam bentuk simbol bintang
     */
    public function getBintangRating(): string
    {
        $bintangUtuh = (int) round($this->rating);
        $bintangUtuh = max(1, min(5, $bintangUtuh));
        return str_repeat('★', $bintangUtuh) . str_repeat('☆', 5 - $bintangUtuh);
    }

    /**
     * Konversi objek menjadi representasi Array Asosiatif
     */
    public function toArray(): array
    {
        return [
            'kode'           => $this->kode,
            'kodeBuku'       => $this->kodeBuku,
            'judul'          => $this->judul,
            'pengarang'      => $this->pengarang,
            'tahun'          => $this->tahun,
            'rating'         => $this->rating,
            'dipinjam'       => $this->dipinjam,
            'status'         => $this->status,
            'hari_terlambat' => $this->hariTerlambat,
            'hariTerlambat'  => $this->hariTerlambat,
            'kategori'       => $this->kategori,
            'denda'          => $this->hitungDenda(),
        ];
    }

    /**
     * Factory method untuk membuat objek dari data array
     */
    public static function fromArray(array $data): self
    {
        $kode           = $data['kode'] ?? $data['kodeBuku'] ?? 'BK000';
        $judul          = $data['judul'] ?? 'Tanpa Judul';
        $dipinjam       = $data['dipinjam'] ?? ($data['status'] ?? false);
        $hariTerlambat  = (int) ($data['hari_terlambat'] ?? ($data['hariTerlambat'] ?? 0));
        $pengarang      = $data['pengarang'] ?? 'Anonim';
        $tahun          = (int) ($data['tahun'] ?? 2024);
        $rating         = (float) ($data['rating'] ?? 4.0);
        $kategori       = $data['kategori'] ?? 'Umum';

        return new self($kode, $judul, $dipinjam, $hariTerlambat, $pengarang, $tahun, $rating, $kategori);
    }
}
