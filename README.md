# Pustaka Cendekia
### Pemrograman Berbasis Web (Kelas B)

Aplikasi web PHP ini mengintegrasikan seluruh materi perkuliahan Pertemuan 1 sampai 7: paradigma pemrograman PHP, logika prosedural, class dan object, variabel dan tipe data, operator, manipulasi teks, serta struktur kontrol perulangan while, do-while, dan for.

---

## Profil Kelompok dan Pembagian Tugas

**Kelompok 7**

| No | Nama Mahasiswa | NPM | Peran Kelompok | Tanggung Jawab |
|---|---|---|---|---|
| 1 | Alfin Romi Setiawan | 4524210009 | Tester & Dokumentasi | Memeriksa keabsahan kalkulasi, menyusun dokumentasi, dan halaman formulir transaksi, pencarian, dan simulasi sirkulasi (`proses.php`) |
| 2 | Alifian Revan Arnanta | 4524210010 | Presenter / UI Integrator | Merancang tata letak antarmuka (`assets/css/style.css`), navigasi, dashboard, dan demo aplikasi. |
| 3 | Az-Zahra Putri | 4524210018 | PHP Developer A | Menulis logika prosedural (`functions.php`), fungsi perhitungan denda, sanitasi keamanan teks, dan penanganan input, dan data array koleksi buku perpustakaan (`data/sample.php`) |
| 4 | Rakha Hafidz Fauzan | 4524210080 | Koordinator / System Analyst | Menyusun alur sistem, mengintegrasikan antarmuka dan modul, memastikan seluruh syarat P1–P7 terpenuhi, dan halaman utama, ringkasan metrik, dan navigasi (`index.php`) |
| 5 | Putri Wahyu Dwi Nurfatimah | 4524210082 | PHP Developer B | Menulis class OOP `Buku` (`classes/Buku.php`), struktur perulangan for, dan tabel laporan (`laporan.php`). |

---

## Matriks Pemenuhan Syarat Wajib (Pertemuan 1–7)

Rincian pemenuhan checklist tugas:

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

## Susunan Berkas Proyek

```text
miniapp-perpustakaan/
├── index.php            
├── proses.php            
├── laporan.php           
├── functions.php             
├── README.md             
├── classes/
│   └── Buku.php          
├── data/
│   └── sample.php        
└── assets/
    ├── css/
    │   └── style.css     
    └── images/        
```

---

## Petunjuk Menjalankan di XAMPP

1. Nyalakan modul Apache melalui XAMPP Control Panel.
2. Pastikan folder berada di direktori `htdocs`:
   `C:\xampp\htdocs\pemrograman-berbasis-web\miniapp-perpustakaan`
3. Buka browser dan jalankan alamat berikut:
   * Beranda: `http://localhost/pemrograman-berbasis-web/miniapp-perpustakaan/index.php`
   * Transaksi: `http://localhost/pemrograman-berbasis-web/miniapp-perpustakaan/proses.php`
   * Laporan: `http://localhost/pemrograman-berbasis-web/miniapp-perpustakaan/laporan.php`

---

## Catatan Kendala dan Solusi Teknis

1. **Plafon Sanksi Keterlambatan**
   Keterlambatan dalam durasi panjang berpotensi menimbulkan nilai denda tidak wajar. Kami menetapkan konstanta `DENDA_MAKSIMAL = 50000` dengan operator ternary pembanding, sehingga hasil perhitungan selalu berada dalam batas aturan perpustakaan.
2. **Pencegahan Celah Keamanan XSS**
   Data dari formulir dan katalog berisiko disisipi skrip jahat bila dicetak langsung. Seluruh output teks dilewatkan melalui fungsi `amankanTeks()` yang memanggil `htmlspecialchars($teks, ENT_QUOTES, 'UTF-8')`.