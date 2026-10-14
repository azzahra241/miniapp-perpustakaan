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
     * Menjamin bahwa properti status, dipinjam, dan hariTerlambat selalu sinkron
     */
    public function __construct(
        string $kode,
        string $judul,
        string|bool $statusAtauDipinjam = 'Tersedia',
        int $hariTerlambat = 0,
        string $pengarang = 'Penulis Tidak Diketahui',
        int $tahun = 2024,
        float $rating = 4.5,
        string $kategori = 'Umum'
    ) {
        $this->kode          = $kode;
        $this->kodeBuku      = $kode;
        $this->judul         = $judul;
        $this->pengarang     = $pengarang;
        $this->tahun         = $tahun;
        $this->rating        = $rating;
        $this->kategori      = $kategori;

        // [P4] Logika sinkronisasi status:
        if (is_string($statusAtauDipinjam)) {
            $cleanStatus = ucfirst(strtolower(trim($statusAtauDipinjam)));
            if ($cleanStatus === 'Tersedia') {
                $this->status = 'Tersedia';
                $this->dipinjam = false;
                $this->hariTerlambat = 0;
            } elseif ($cleanStatus === 'Dipinjam') {
                $this->status = 'Dipinjam';
                $this->dipinjam = true;
                $this->hariTerlambat = 0;
            } elseif ($cleanStatus === 'Terlambat') {
                $this->status = 'Terlambat';
                $this->dipinjam = true;
                $this->hariTerlambat = $hariTerlambat > 0 ? $hariTerlambat : 1;
            } else {
                $this->status = $statusAtauDipinjam;
                $this->dipinjam = false;
                $this->hariTerlambat = 0;
            }
        } else {
            $this->dipinjam = (bool) $statusAtauDipinjam;
            if (!$this->dipinjam) {
                $this->status = 'Tersedia';
                $this->hariTerlambat = 0;
            } else {
                $this->hariTerlambat = max(0, $hariTerlambat);
                $this->status = $this->hariTerlambat > 0 ? 'Terlambat' : 'Dipinjam';
            }
        }
    }

    /**
     * [P2] Method menghitung denda keterlambatan buku
     * [P4] Menggunakan operator aritmatika dan ternary
     */
    public function hitungDenda(int $tarif = 1000, int $maksimal = 50000): int
    {
        if ($this->status === 'Tersedia' || !$this->dipinjam || $this->hariTerlambat <= 0) {
            return 0;
        }
        $total = $this->hariTerlambat * $tarif;
        return $total > $maksimal ? $maksimal : $total;
    }

    public function isTerlambat(): bool
    {
        return $this->status === 'Terlambat' || ($this->dipinjam && $this->hariTerlambat > 0);
    }

    public function getStatusPinjam(): string
    {
        return $this->status;
    }

    public function formatRupiahDenda(): string
    {
        return 'Rp ' . number_format($this->hitungDenda(), 0, ',', '.');
    }

    public function getBintangRating(): string
    {
        $bintangUtuh = (int) round($this->rating);
        $bintangUtuh = max(1, min(5, $bintangUtuh));
        return str_repeat('★', $bintangUtuh) . str_repeat('☆', 5 - $bintangUtuh);
    }

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
        $kode          = $data['kode'] ?? $data['kodeBuku'] ?? 'BK000';
        $judul         = $data['judul'] ?? 'Tanpa Judul';
        $pengarang     = $data['pengarang'] ?? 'Anonim';
        $tahun         = (int) ($data['tahun'] ?? 2024);
        $rating        = (float) ($data['rating'] ?? 4.0);
        $kategori      = $data['kategori'] ?? 'Umum';
        $hariTerlambat = (int) ($data['hari_terlambat'] ?? ($data['hariTerlambat'] ?? 0));

        // Mengutamakan nilai status jika diberikan dalam array
        if (isset($data['status']) && is_string($data['status'])) {
            return new self($kode, $judul, $data['status'], $hariTerlambat, $pengarang, $tahun, $rating, $kategori);
        }

        $dipinjam = (bool) ($data['dipinjam'] ?? false);
        return new self($kode, $judul, $dipinjam, $hariTerlambat, $pengarang, $tahun, $rating, $kategori);
    }
}
?>
