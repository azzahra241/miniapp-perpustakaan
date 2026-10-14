# Pustaka Cendekia: Sistem Sirkulasi Perpustakaan
### Proyek Integrasi Pertemuan 1–7 | Pemrograman Berbasis Web (Kelas B)

Aplikasi web PHP ini mengintegrasikan seluruh materi perkuliahan Pertemuan 1 sampai 7: paradigma pemrograman PHP, logika prosedural, class dan object, variabel dan tipe data, operator, manipulasi teks, serta struktur kontrol perulangan while, do-while, dan for.

---

## Profil Kelompok dan Pembagian Tugas

**Kelompok 7, Pemrograman Berbasis Web Kelas B**

| No | Nama Mahasiswa | NPM | Peran Kelompok (Sesuai Slide 4) | Tanggung Jawab Utama |
|---|---|---|---|---|
| 1 | Alfin Romi Setiawan | 4524210009 | Koordinator / System Analyst | Menyusun alur sistem, mengintegrasikan antarmuka dan modul, memastikan seluruh syarat P1–P7 terpenuhi. |
| 2 | Alifian Revan Arnanta | 4524210010 | PHP Developer A | Menulis logika prosedural (`functions.php`), fungsi perhitungan denda, sanitasi keamanan teks, dan penanganan input. |
| 3 | Az-Zahra Putri | 4524210018 | PHP Developer B | Menulis class OOP `Buku` (`classes/Buku.php`), struktur perulangan for, dan tabel laporan (`laporan.php`). |
| 4 | Rakha Hafidz Fauzan | 4524210080 | Tester & Dokumentasi | Menyiapkan skenario uji (`uji-functions.php`), memeriksa keabsahan kalkulasi, dan menyusun dokumentasi. |
| 5 | Putri Wahyu Dwi Nurfatimah | 4524210082 | Presenter / UI Integrator | Merancang tata letak antarmuka (`assets/css/style.css`), navigasi, dashboard, dan demo aplikasi. |

---

## Matriks Pemenuhan Syarat Wajib (Pertemuan 1–7)

Rincian pemenuhan checklist tugas berdasarkan Slide 10:

| Pertemuan | Syarat Wajib di Slide | Bukti Implementasi | Lokasi Berkas dan Baris Kode |
|---|---|---|---|
| P1 | XAMPP, localhost, sintaks PHP dasar | Berjalan pada server lokal Apache XAMPP dengan pemisahan berkas yang rapi. | `index.php`, `proses.php`, `laporan.php` |
| P2 | Minimal 2 function procedural + 1 class/object | Fungsi prosedural `normalisasiJudul()`, `formatRupiah()`, `hitungDenda()`, serta class `Buku`. | `functions.php` (baris 46-120) dan `classes/Buku.php` (baris 10-115) |
| P3 | 5 tipe data + konstanta + konversi | Menggunakan tipe string, int, float, bool, array, konstanta `DENDA_PER_HARI`, serta casting `(int)`, `(float)`. | `functions.php` (baris 20-37 dan 124) |
| P4 | Operator lengkap + minimal 4 teknik string | Operator `+`, `-`, `*`, `%`, `intdiv`, `>`, `===`, `!`, `&&`, `?:`. Serta teknik string `trim()`, `preg_replace()`, `strtoupper()`, `ucwords()`, `str_pad()`, `htmlspecialchars()`, `number_format()`, `str_contains()`. | `functions.php` (baris 46-118) dan `proses.php` |
| P5 | 1 implementasi WHILE | Pencarian data buku dan pemrosesan antrean pengembalian bertahap. | `functions.php` (baris 137-205) dan `proses.php` |
| P6 | 1 implementasi DO-WHILE | Siklus menu transaksi dengan jaminan eksekusi minimal satu kali sebelum terminasi. | `functions.php` (baris 215-240) dan `proses.php` |
| P7 | 1 implementasi FOR + output tabel/list | Render tabel data koleksi dan nested loop for untuk pemetaan unit eksemplar rak serta rating bintang. | `laporan.php` (baris 125-220) |

---

## Susunan Berkas Proyek (Sesuai Slide 11)

```text
miniapp-perpustakaan/
├── index.php             # Halaman utama, ringkasan metrik, dan tabel matriks syarat
├── proses.php            # Halaman proses sirkulasi: pencarian while, kalkulator denda, simulasi do-while
├── laporan.php           # Halaman laporan: tabel koleksi OOP, visualisasi unit rak bertingkat (nested for)
├── functions.php         # Kumpulan fungsi prosedural, konstanta, sanitasi teks, dan kalkulasi
├── uji-functions.php     # Pengujian unit mandiri untuk memeriksa fungsi dan perulangan
├── README.md             # Dokumentasi proyek, susunan tim, dan panduan menjalankan
├── classes/
│   └── Buku.php          # Definisi Class OOP Buku dengan properti dan method objek
├── data/
│   └── sample.php        # Kumpulan data 12 buku dan inisialisasi objek Buku
└── assets/
    ├── css/
    │   └── style.css     # Tata letak visual editorial dan format media cetak
    └── images/           # Direktori aset gambar
```

---

## Hubungan dengan Folder TUGAS_UTS

1. Kedua folder menggunakan Class `Buku` yang identik. Properti `kodeBuku`, `status`, dan `hariTerlambat` pada berkas lama tetap berfungsi normal, berdampingan dengan properti katalog baru seperti `pengarang`, `tahun`, `rating`, dan `kategori`.
2. Berkas `data/sample.php` dapat dipanggil sebagai array biasa maupun menyediakan array objek `$daftarBuku` untuk kebutuhan modul Developer B.
3. Seluruh alur dapat diakses dari kedua folder melalui browser tanpa kendala tautan terputus.

---

## Petunjuk Menjalankan di XAMPP

1. Nyalakan modul Apache melalui XAMPP Control Panel.
2. Pastikan folder berada di direktori `htdocs`:
   `C:\xampp\htdocs\pemrograman-berbasis-web\miniapp-perpustakaan`
3. Buka browser dan jalankan alamat berikut:
   * Beranda: `http://localhost/pemrograman-berbasis-web/miniapp-perpustakaan/index.php`
   * Transaksi: `http://localhost/pemrograman-berbasis-web/miniapp-perpustakaan/proses.php`
   * Laporan: `http://localhost/pemrograman-berbasis-web/miniapp-perpustakaan/laporan.php`
   * Pengujian: `http://localhost/pemrograman-berbasis-web/miniapp-perpustakaan/uji-functions.php`
   * Modul Developer B: `http://localhost/pemrograman-berbasis-web/TUGAS_UTS/developer-b.php`

---

## Catatan Kendala dan Solusi Teknis

1. **Penyesuaian Struktur Antara Anggota Tim**
   Kode awal pada `miniapp-perpustakaan` memakai array asosiatif murni, sedangkan `TUGAS_UTS` menggunakan class dengan penamaan properti `kodeBuku`. Solusinya, constructor `Buku` dibuat fleksibel untuk mengisi `kode` dan `kodeBuku` sekaligus, serta menambahkan method `fromArray()`.
2. **Plafon Sanksi Keterlambatan**
   Keterlambatan dalam durasi panjang berpotensi menimbulkan nilai denda tidak wajar. Kami menetapkan konstanta `DENDA_MAKSIMAL = 50000` dengan operator ternary pembanding, sehingga hasil perhitungan selalu berada dalam batas aturan perpustakaan.
3. **Pencegahan Celah Keamanan XSS**
   Data dari formulir dan katalog berisiko disisipi skrip jahat bila dicetak langsung. Seluruh output teks dilewatkan melalui fungsi `amankanTeks()` yang memanggil `htmlspecialchars($teks, ENT_QUOTES, 'UTF-8')`.