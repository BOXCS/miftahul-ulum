# User Manual Teknis - Integrasi Sistem Sidik Jari (Fingerprint)

Dokumen ini berisi panduan teknis untuk melakukan instalasi perangkat, pemecahan masalah (troubleshooting), dan manajemen data untuk fitur pemindai sidik jari di aplikasi Miftahul Ulum.

## 1. Instalasi Driver Perangkat (Fingerprint Scanner)

Aplikasi ini dirancang untuk kompatibel dengan pemindai sidik jari yang menggunakan integrasi Web API / Web SDK (seperti DigitalPersona, SecuGen, ZKTeco, atau perangkat biometrik sejenis). 

### Langkah-langkah Instalasi:
1. Hubungkan perangkat Fingerprint Scanner ke port USB di komputer atau laptop yang akan digunakan oleh admin/petugas absensi.
2. Download driver dan Web SDK resmi dari vendor scanner yang Anda gunakan (contoh: *U.are.U SDK* untuk DigitalPersona atau *SecuGen WebAPI*).
3. Jalankan file installer (`.exe` atau `.msi`) dan ikuti instruksi di layar hingga selesai.
4. Restart komputer untuk memastikan layanan (services) berjalan dengan baik di latar belakang.
5. Pastikan layanan Web SDK (biasanya berjalan di `localhost` pada port khusus seperti 8080, 15000, atau 8443) aktif dan tidak diblokir oleh firewall atau antivirus komputer.

## 2. Troubleshooting Common Issues (Pemecahan Masalah Umum)

### A. Perangkat Tidak Terhubung atau Status Tetap "Scanning" Tanpa Hasil
- **Penyebab**: Scanner belum tercolok sempurna, driver belum terinstal benar, atau service Web API belum berjalan.
- **Solusi**:
  1. Cabut dan colok kembali perangkat scanner ke port USB lain (disarankan langsung ke port motherboard jika menggunakan PC Desktop).
  2. Buka aplikasi `Services.msc` di Windows, dan cari service dari vendor (misal "DigitalPersona Web API", "SecuGen Service", atau "ZKTeco Biometric Service"). Pastikan statusnya `Running`.
  3. Cek pengaturan browser. Terkadang browser memblokir koneksi ke `localhost` dari situs HTTPS (Mixed Content). Pastikan koneksi ke SDK diizinkan.

### B. Kualitas Scan Buruk (Sering Gagal)
- **Penyebab**: Jari kotor, basah, terlalu kering, atau penempatan jari tidak pas di area sensor.
- **Solusi**:
  1. Bersihkan jari sebelum memindai. Jika jari terlalu kering, lembabkan sedikit (misal menggunakan hand sanitizer/lap basah lalu keringkan).
  2. Pastikan menekan sensor secara merata dengan penempatan area tengah sidik jari (pusat ulir), bukan hanya ujung jari.
  3. Saat pendaftaran, lakukan **Re-scan** hingga indikator kualitas menunjukkan persentase memadai (minimal >80%).

### C. Sidik Jari Tidak Dikenali (Saat Absensi)
- **Penyebab**: Jari yang digunakan berbeda dengan saat pendaftaran awal, atau kualitas template yang disimpan memiliki resolusi rendah.
- **Solusi**:
  1. Pastikan santri menggunakan jari yang sama persis seperti saat didaftarkan.
  2. Pastikan kaca sensor bersih dari debu atau bekas sidik jari orang sebelumnya.
  3. Jika masalah terus berlanjut berulang kali, Admin dapat menghapus data sidik jari sebelumnya dan mendaftarkan ulang (Enroll) sidik jari baru melalui halaman Data Santri.

## 3. Prosedur Backup dan Restore Data Sidik Jari

Data biometrik sangat krusial. Data sidik jari ini disimpan dalam format terenkripsi (AES-256) pada kolom `fingerprint_template` di tabel `students`. Kunci enkripsi menggunakan `APP_KEY` yang ada di file `.env` Laravel.

### A. Backup Data
Sangat disarankan untuk melakukan backup database secara harian/mingguan.
- Melalui Command Line (MySQL/MariaDB):
  ```bash
  mysqldump -u username -p miftahul_ulum students > backup_students_fingerprint_$(date +%F).sql
  ```
- **PENTING**: Anda **WAJIB** menyimpan salinan / backup dari file `.env`, terutama baris `APP_KEY=...`. Tanpa kunci enkripsi yang sama, seluruh data template sidik jari yang ada di database tidak akan bisa didekripsi, sehingga fitur absensi tidak akan berfungsi saat restore di server baru.

### B. Restore Data
Jika terjadi kerusakan sistem dan Anda perlu memulihkan data:
- Melalui Command Line:
  ```bash
  mysql -u username -p miftahul_ulum < backup_students_fingerprint_YYYY-MM-DD.sql
  ```
- Pastikan versi file `.env` yang digunakan di server pemulihan memiliki `APP_KEY` yang cocok/sama persis dengan yang dipakai saat data tersebut di-backup.

## 4. Keamanan Data (Security Measures)

- **Enkripsi AES-256:** Seluruh string/binary template dari scanner tidak disimpan dalam bentuk *plaintext*. Data otomatis dienkripsi sebelum masuk database.
- **Access Control:** Hanya pengguna dengan peran Admin/Petugas yang berhasil masuk (login) ke aplikasi dan memiliki hak akses ke modul Data Santri yang dapat mengubah (add/edit) data sidik jari.
- **Isolasi Proses:** Proses matching 1:N (Identifikasi pada Absensi) dilakukan dengan mendekripsi template langsung di level server sesaat sebelum matching, mencegah data biometrik terekspos secara publik.