# Fitur Sistem Manajemen Tugas

## Deskripsi Sistem
Sistem manajemen tugas internal yang dirancang untuk memantau pekerjaan harian tim internal perusahaan seperti IT, HR, dan Operasional. Sistem ini menggantikan pelacakan manual dengan platform terpusat yang memudahkan manajemen proyek dan tugas.

## Fitur Utama

### 1. Manajemen Pengguna
- **Autentikasi dan Otorisasi**: Sistem login yang aman dengan pengelolaan hak akses berbasis peran (RBAC)
- **Multi-departemen**: Mendukung pengguna dari berbagai departemen (IT, HR, Operasional)
- **Profil Pengguna**: Setiap pengguna memiliki profil dengan informasi departemen, posisi, dan kontak
- **Status Aktif/Non-aktif**: Kemampuan untuk menonaktifkan akun pengguna

### 2. Manajemen Proyek
- **Pembuatan Proyek**: Membuat proyek baru dengan detail seperti nama, deskripsi, tanggal mulai/selesai
- **Status Proyek**: Melacak status proyek (planning, in_progress, completed, on_hold, cancelled)
- **Penugasan Manajer**: Setiap proyek dapat ditugaskan ke seorang manajer proyek
- **Prioritas**: Pengaturan prioritas proyek (low, medium, high)
- **Anggaran**: Pencatatan anggaran proyek
- **Soft Delete**: Proyek dapat dihapus sementara dan dipulihkan jika diperlukan

### 3. Manajemen Tugas
- **Penugasan Tugas**: Tugas dapat ditugaskan ke pengguna tertentu
- **Status Tugas**: Pelacakan status (todo, in_progress, done)
- **Tenggat Waktu**: Pengaturan tanggal mulai dan tenggat waktu
- **Prioritas Tugas**: Pengaturan prioritas (low, medium, high)
- **Estimasi Waktu**: Pencatatan estimasi dan waktu aktual yang dihabiskan
- **Catatan**: Menambahkan catatan tambahan pada tugas
- **Relasi dengan Proyek**: Setiap tugas terhubung dengan proyek tertentu

### 4. Sistem Komentar
- **Komentar pada Tugas**: Pengguna dapat menambahkan komentar pada tugas
- **Log Otomatis**: Sistem dapat membuat log otomatis untuk perubahan status
- **Riwayat Diskusi**: Semua komentar tersimpan dengan timestamp

### 5. Pelaporan dan Monitoring
- **Dashboard Admin**: Tampilan ringkas status semua proyek dan tugas
- **Dashboard User**: Tampilan tugas yang ditugaskan ke pengguna
- **Laporan Status**: Ringkasan jumlah tugas berdasarkan status untuk setiap proyek dan pengguna
- **Agregat Status**: Statistik agregat untuk todo, in progress, dan done

### 6. Hak Akses (Role-Based Access Control)
- **Role Management**: Pengelolaan peran dan hak akses
- **Permission Control**: Kontrol granular untuk setiap fitur
- **Admin Access**: Akses penuh untuk mengelola sistem
- **User Access**: Akses terbatas sesuai dengan penugasan

### 7. Fitur Tambahan
- **Soft Delete**: Data tidak dihapus permanen, dapat dipulihkan
- **Index Database**: Optimasi query dengan indexing untuk performa yang lebih baik
- **Pelacakan Waktu**: Monitoring waktu yang dihabiskan untuk setiap tugas
- **Fleksibilitas Status**: Status proyek dan tugas yang dapat disesuaikan

## Teknologi yang Digunakan
- **Framework**: Laravel
- **Admin Panel**: Filament
- **Database**: MySQL
- **Authentication**: Laravel Auth dengan Filament Shield untuk RBAC

## Manfaat Sistem
1. **Sentralisasi**: Semua tugas dan proyek tercatat dalam satu sistem
2. **Transparansi**: Visibilitas penuh tentang siapa mengerjakan apa
3. **Efisiensi**: Mengurangi waktu yang dihabiskan untuk pelacakan manual
4. **Akuntabilitas**: Jelas siapa yang bertanggung jawab untuk setiap tugas
5. **Fleksibilitas**: Dapat disesuaikan untuk berbagai departemen
6. **Skalabilitas**: Dapat dikembangkan sesuai kebutuhan perusahaan

## Use Case Utama
1. **Administrator**:
   - Mengelola pengguna dan hak akses
   - Membuat dan mengelola proyek
   - Melihat laporan keseluruhan
   - Mengatur konfigurasi sistem

2. **Manajer Proyek**:
   - Membuat dan mengelola proyek yang ditugaskan
   - Menugaskan tugas ke anggota tim
   - Memantau progres proyek
   - Melihat laporan tim

3. **Pengguna Reguler**:
   - Melihat tugas yang ditugaskan
   - Memperbarui status tugas
   - Menambahkan komentar pada tugas
   - Melihat proyek yang terkait

Sistem ini dirancang untuk meningkatkan produktivitas dan kolaborasi tim dengan menyediakan platform terpusat untuk manajemen tugas yang efektif dan efisien.
