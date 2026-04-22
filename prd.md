# Product Requirements Document (PRD)

**Project Name:** Sistem Informasi Manajemen Madrasah Terpadu (SIMAD)
**Document Version:** 3.0 (Final Consolidated)
**Target Deployment:** Local Server (LAN) & Cloud Sync for Mobile App

## 1. Product Overview

SIMAD adalah sistem informasi terintegrasi berskala _enterprise_ untuk mengelola operasional akademik pesantren/madrasah. Sistem ini menaungi lebih dari 1000 santri dari berbagai tingkatan pendidikan (TK, MI, MTs, MA/Ulya). Aplikasi ini memfasilitasi pencatatan akademik, presensi, riwayat historis murni, dan pelaporan yang dapat diakses oleh berbagai pemangku kepentingan sesuai hak akses.

## 2. Architectural Principles & Tech Stack

Sistem dibangun dengan arsitektur Monolitik untuk Web/Backend dan terpisah untuk Mobile App.

- **Backend API & Web Admin:** PHP (Laravel Framework).
- **Web Frontend (UI):** Tailwind CSS (terintegrasi dengan Laravel Blade / Inertia.js).
- **Mobile App (Wali Santri):** Flutter (iOS & Android).
- **Database:** MySQL atau PostgreSQL.
- **Prinsip Arsitektur Utama:**
    1. **Multi-Level Isolation:** Data dipisahkan berdasarkan Tingkat Pendidikan (`education_level_id`).
    2. **Historical Integrity:** Menerapkan konsep _Insert-Only_ pada data transaksional (kelas siswa). Data lama tidak pernah ditimpa (overwrite).
    3. **Dynamic Curriculum:** Mata pelajaran dikelola dinamis per Tahun Ajaran dan Tingkat Kelas.

## 3. User Roles & Access Control (RBAC)

Sistem menggunakan pembatasan _scope_ berdasarkan unit sekolah.

1. **Super Admin (Yayasan/Pusat):** Akses penuh ke semua tingkatan. Mengelola konfigurasi sistem dan _database backup_. (`education_level_id` = NULL)
2. **Kepala Sekolah:** Akses _Read-Only_ ke _dashboard_ analitik (Presensi, Nilai) khusus untuk tingkatan sekolah yang dipimpinnya.
3. **Guru Mata Pelajaran:** Mengisi presensi dan menginput nilai sesuai kelas dan mapel yang diampu.
4. **Wali Kelas:** Memiliki hak Guru, ditambah hak memvalidasi nilai akhir kelasnya dan _generate_ E-Raport.
5. **Wali Santri:** Mengakses aplikasi _mobile_ untuk melihat riwayat akademik dan presensi seluruh anak tanggungannya (lintas tingkatan).

## 4. Core Business Logic & Workflow

### 4.1. Modul Akademik & Kurikulum Dinamis

- **Setup Kurikulum:** Admin meracik mata pelajaran per "Tingkat Kelas" setiap awal tahun ajaran.
- **Penilaian:** Form input nilai otomatis menyesuaikan jumlah mapel dan KKM yang berlaku di tahun ajaran tersebut.

### 4.2. Logika Tahun Ajaran Baru & Kenaikan Kelas

Proses ini krusial untuk menjaga integritas data historis:

- **Persiapan:** Admin membuat _record_ Tahun Ajaran Baru dan menduplikasi/menyesuaikan struktur Kurikulum.
- **Eksekusi (Kenaikan):** Sistem **TIDAK** melakukan `UPDATE` pada data kelas siswa. Sistem melakukan `INSERT` baris baru ke tabel `student_histories` dengan `academic_year_id` dan `class_id` yang baru.
- **Kelulusan/Mutasi Tingkat:** Jika santri MTs lulus dan lanjut ke MA/Ulya, `current_level_id` pada master `students` di-_update_ menjadi ID Ulya, memindahkan wewenang data ke Kepala Sekolah Ulya, sementara histori MTs-nya tetap utuh.

### 4.3. Modul Presensi & E-Raport

- **Presensi:** Input harian (Hadir, Sakit, Izin, Alpha) dengan kalkulasi persentase otomatis.
- **E-Raport:** _Generate_ PDF dinamis di akhir semester berdasarkan data kurikulum pivot dan nilai yang masuk.

---

## 5. Database Schema & ERD Specification

### 5.1. Master Data (Sekolah & Hierarki)

- **`education_levels`** (Unit/Tingkat Sekolah)
    - `id` (PK) | `kode` (ex: 'MTS') | `nama` (ex: 'Madrasah Tsanawiyah')
- **`grade_levels`** (Tingkat Kelas/Jurusan)
    - `id` (PK) | `education_level_id` (FK) | `nama_tingkat` (ex: 'Kelas 7')
- **`classes`** (Ruang Kelas Fisik)
    - `id` (PK) | `grade_level_id` (FK) | `nama_kelas` (ex: '7-A') | `wali_kelas_id` (FK -> `teachers.id`)
- **`academic_years`** (Tahun Ajaran)
    - `id` (PK) | `nama` (ex: '2025/2026') | `semester` (Enum) | `is_active` (Boolean)

### 5.2. Master Data (Pengguna, Wali & Siswa)

- **`users`** (Hak Akses & Login)
    - `id` (PK) | `username`/`email` | `password` | `role` (Enum) | `education_level_id` (FK, Nullable)
- **`wali_santri`** (Wali Utama / User Mobile)
    - `id` (PK, BigInt)
    - `user_id` (FK -> `users.id`, Nullable)
    - `nik` (String, 16 chars, Unique)
    - `nama_lengkap` (String)
    - `hubungan_keluarga` (Enum) | `pendidikan_terakhir` (Enum)
    - `pekerjaan` (String) | `penghasilan_bulanan` (Enum)
    - `no_whatsapp` (String, Unique) -> _Untuk login OTP mobile app._
    - `alamat_lengkap` (Text)
    - `deleted_at` (Timestamp) -> _Soft delete._
- **`students`** (Data Statis Santri)
    - `id` (PK, BigInt)
    - `nisn` (String, 10 chars, Unique, Nullable)
    - `no_kk` (String, 16 chars)
    - `nik` (String, 16 chars, Unique)
    - `nama_lengkap` (String)
    - `tempat_lahir` (String) | `tanggal_lahir` (Date)
    - `jenis_kelamin` (Enum: 'L', 'P')
    - `current_level_id` (FK -> `education_levels.id`)
    - `wali_id` (FK -> `wali_santri.id`)
    - `status_aktif` (Enum: 'Aktif', 'Lulus', 'Mutasi', 'Keluar')
    - `deleted_at` (Timestamp)

### 5.3. Relasi Dinamis & Historis (Core Engine)

- **`student_histories`** (Riwayat Kelas Siswa)
    - `id` (PK) | `student_id` (FK) | `academic_year_id` (FK) | `class_id` (FK) | `status_kenaikan` (Enum)
- **`teachers`** (Data Master Guru)
    - `id` (PK) | `user_id` (FK) | `nip` (String) | `nama_lengkap` (String) | `deleted_at` (Timestamp)
- **`teacher_assignments`** (Riwayat Mengajar & Wali Kelas)
    - `id` (PK) | `teacher_id` (FK) | `academic_year_id` (FK) | `class_id` (FK) | `subject_id` (FK) | `is_wali_kelas` (Boolean)

### 5.4. Kurikulum & Transaksional

- **`subjects`** (Master Mapel Global)
    - `id` (PK) | `kode_mapel` (String) | `nama_mapel` (String)
- **`curriculums`** (Pemetaan Kurikulum - Pivot)
    - `id` (PK) | `academic_year_id` (FK) | `grade_level_id` (FK) | `subject_id` (FK) | `kkm` (Integer)
- **`attendances`** (Presensi)
    - `id` (PK) | `student_id` (FK) | `class_id` (FK) | `tanggal` (Date) | `status` (Enum)
- **`grades`** (Penilaian)
    - `id` (PK) | `student_id` (FK) | `curriculum_id` (FK) | `jenis_nilai` (Enum) | `nilai` (Float)

---

## 6. Security, API & Deployment Strategy

1. **Global Scopes (Laravel):** Implementasi wajib pada model Eloquent untuk memfilter data otomatis berdasarkan `education_level_id` _user_ yang _login_.
2. **Soft Deletes:** Wajib digunakan pada tabel entitas manusia (`users`, `students`, `teachers`, `wali_santri`).
3. **Database Transactions (`DB::transaction`):** Digunakan untuk proses krusial seperti Kenaikan Kelas masal dan Pendaftaran Siswa Baru untuk mencegah _corrupt data_.
4. **Data Validation:** Ketentuan ketat pada _API Requests_, misalnya: `nik` wajib `string|size:16|unique`.
5. **Deployment Lokal:** \* Server ditempatkan di madrasah menggunakan XAMPP/Laragon.
    - Diakses secara lokal (LAN) oleh Guru dan Admin.
    - Untuk mengakomodasi aplikasi _mobile_ Wali Santri, server lokal dapat dihubungkan ke internet menggunakan IP Publik Statis atau melakukan sinkronisasi asinkron ke _Cloud VPS_ kecil (API Gateway).
