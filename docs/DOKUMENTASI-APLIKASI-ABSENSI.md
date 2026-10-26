# Dokumentasi Aplikasi Absensi & Patroli Keamanan

Dokumen ini adalah spesifikasi produk dan arsitektur aplikasi yang akan dikembangkan berdasarkan struktur database `schema.sql` dan kebutuhan user yang sudah dijelaskan.

## 1. Tujuan Aplikasi
Aplikasi ini bertujuan untuk:
- Mengelola absensi karyawan secara digital
- Menggunakan geofence lokasi untuk validasi kehadiran
- Menggunakan deteksi wajah untuk verifikasi identitas pengguna
- Mengelola patroli keamanan di titik-titik tertentu
- Menyediakan dashboard untuk user dan admin
- Menyediakan sistem izin, pengaturan, dan riwayat data attendance

## 2. User / Role Aplikasi
### 2.1 Role User
- Karyawan / Pegawai
- Melakukan login, absensi, lihat riwayat, profil, pengaturan

### 2.2 Role Admin
- Mengelola lokasi, jadwal, pengguna, izin, laporan, patrol
- Mengatur jam kerja, toleransi keterlambatan, dan data cabang

### 2.3 Role Super Admin / Chief
- Mengakses seluruh cabang
- Mengelola setting global dan data strategis

## 3. Flow Register & Onboarding
Flow onboarding sangat penting karena user menuntut proses register yang aman dan lengkap.

### 3.1 Register Akun Baru
Saat user baru mendaftar, sistem wajib meminta data berikut:
- NIK
- Nama lengkap
- Jabatan (Chief / Danru / Anggota)
- Email atau username
- Password
- Kode lokasi / lokasi kerja
- Foto profil awal (opsional)

### 3.2 Kode Lokasi
- Pada saat register, user harus memilih/masukkan kode lokasi atau cabang aktif
- Kode lokasi harus sesuai dengan data yang ada di tabel `locations`
- Ini digunakan agar user terkait langsung masuk ke area kerja yang benar

### 3.3 Verifikasi Wajah (Mandatory)
Setelah register, sistem mewajibkan user melakukan capture wajah:
1. Foto wajah depan
2. Foto wajah kanan
3. Foto wajah kiri
4. Sistem menyimpan embedding wajah ke field `face_embedding`
5. Jika 3 foto sudah lengkap, status `face_registered = true`

Flownya:
- User masuk ke page onboarding
- Arahkan kamera ke depan
- Ambil foto wajah depan
- Lanjut kanan
- Lanjut kiri
- Setelah semua selesai, sistem validasi kualitas foto
- Jika valid, simpan data
- User baru bisa masuk ke halaman utama

### 3.4 Ketentuan Face Registration
- Wajah harus jelas, tidak buram
- Tidak menggunakan masker / penutup wajah
- Pencahayaan cukup
- Foto minimal 3 sudut
- Sistem memvalidasi dengan threshold tertentu

## 4. Halaman Utama / Dashboard User
Setelah login berhasil dan onboarding selesai, user masuk ke halaman utama dengan fitur utama:
- Status absensi hari ini
- Jam masuk/pulang
- Status hadir / telat / izin
- Tombol absen masuk / keluar
- Notifikasi hari ini
- Riwayat absensi singkat
- Informasi lokasi kerja

## 5. Bottom Navigation
Bottom navigation wajib ada di sisi pengguna. Struktur umum:
1. Home
2. Absensi
3. Riwayat
4. Patrol
5. Profile

Alternatif jika ingin lebih detail:
- Home
- Absen
- Riwayat
- Lokasi
- Profil

## 6. Fitur Utama User
### 6.1 Login & Register
- Login dengan email/username + password
- Register baru dengan validasi NIK unik
- Reset password opsional
- Session login dengan auth token / Sanctum / JWT berdasarkan backend Laravel

### 6.2 Absensi Masuk / Pulang
User dapat melakukan absensi jika:
- Ada lokasi aktif yang sesuai
- User berada dalam geofence
- Wajah sesuai dengan embedding yang terdaftar
- Waktu masih dalam jam kerja yang valid

Sistem akan mencatat:
- `user_id`
- `type` = masuk atau pulang
- `status` = Hadir / Terlambat / Pulang / Izin / Tanpa Keterangan
- `timestamp_server`
- `lat`, `lng`
- `titik_id`
- `foto_url`
- `face_match_score`
- `device_id`

### 6.3 Kamera Wajah Saat Absen
Saat user absen, sistem membuka kamera dan melakukan:
- Capture foto wajah live
- Bandingkan embedding wajah dengan data yang tersimpan
- Jika cocok, lanjutkan proses absensi
- Jika tidak cocok, tolak absen dan tampilkan notifikasi

Fitur ini harus mengadopsi prinsip:
- Face matching score
- Camera capture realtime
- Cek lokasi saat itu juga
- Simpan foto bukti absensi

### 6.4 Riwayat Absensi
User dapat melihat:
- Riwayat absensi harian
- Riwayat bulanan
- Filter tanggal
- Jenis absensi (masuk / pulang)
- Status hadir / terlambat / izin / alpha
- Jam kerja sebenarnya
- Durasi kerja

### 6.5 Profile User
Profile user berisi:
- Nama lengkap
- NIK
- Jabatan
- Foto profil
- Lokasi kerja
- Device ID
- Status akun aktif
- Tanggal bergabung
- Tombol ubah profil / ganti password

### 6.6 Pengaturan Lengkap
Pengaturan user mencakup:
- Notifikasi
- Bahasa
- Tema gelap/terang
- Keamanan akun
- Ganti password
- Keamanan wajah / re-register face
- Akses lokasi / permission kamera
- Sinkronisasi perangkat
- Mode kerja / shift

Admin juga punya pengaturan global:
- Jam masuk
- Jam pulang
- Toleransi keterlambatan
- Radius geofence lokasi
- Daftar titik patroli
- Daftar shift kerja
- Lokasi aktif/nonaktif

## 7. Fitur Lokasi dan Geofence
### 7.1 Lokasi Kerja
Data lokasi disimpan di tabel `locations`:
- `nama`
- `lat`
- `lng`
- `radius_meter`
- `is_active`

### 7.2 Geofence Validasi
Sistem menggunakan PostGIS `ST_DWithin` / `check_geofence()` untuk mengecek apakah user berada dalam radius kantor.

Aturan:
- Jika user berada di luar radius = absensi ditolak
- Jika di dalam radius = ok
- Jika user masuk dari titik yang berbeda tetapi dalam radius = tetap valid sesuai lokasi aktif

### 7.3 Lokasi Setup
Admin harus dapat:
- Tambah lokasi baru
- Edit lokasi
- Nonaktifkan lokasi
- Ubah radius meter
- Simpan koordinat latitude/longitude

## 8. Fitur Patroli Keamanan
### 8.1 Patrol Checkpoints
Sistem memilik tabel `patrol_checkpoints`:
- `nama`
- `lat`
- `lng`
- `radius_meter`
- `urutan`
- `aktif`

### 8.2 Patrol Logs
Setiap scan patrul di-catat di `patrol_logs`:
- user id
- titik id
- waktu server
- lat/lng user
- foto_url

### 8.3 Alur Patrol
- User membuka fitur patrol
- Menampilkan daftar checkpoint urut
- User memilih titik target
- User scan ke titik tertentu
- Sistem mengecek posisi user dan radius
- Jika valid, simpan log patrol

## 9. Fitur Izin dan Keterlambatan
### 9.1 Izin
User dapat membuat pengajuan izin:
- tanggal
- alasan
- foto pendukung
- status (`pending`, `approved`, `rejected`)

Admin dapat:
- approve / reject
- menambahkan catatan
- melihat riwayat izin

### 9.2 Keterlambatan
Sistem menghitung keterlambatan berdasarkan:
- jam masuk standar
- toleransi menit
- waktu login real

Contoh:
- Jam masuk 07:00
- Toleransi 15 menit
- User masuk 07:20 => terlambat 5 menit

## 10. Pengaturan Sistem & Jadwal
### 10.1 Jam Kerja
Data jam kerja disimpan di `settings`:
- `jam_masuk`
- `jam_pulang`
- `toleransi_menit`

### 10.2 Shift / Durasi / Hari / Tanggal
Fitur yang disyaratkan untuk user:
- jadwal kerja per hari
- tanggal aktif
- jam masuk/pulang
- durasi kerja
- hari kerja
- waktu mulai dan selesai
- jenis shift

Data yang mendukung:
- `attendance`
- `settings`
- `shift` (jika diterapkan)
- `users`

## 11. Flow Pengguna yang Diinginkan
Berikut alur yang sangat cocok untuk user aplikasi yang baru register dan masuk ke homepage:

### 11.1 Flow Baru Register
1. User mendaftar akun
2. Input data NIK, nama, jabatan, lokasi, password
3. Sistem validasi unik NIK dan lokasi
4. User diarahkan ke onboarding face capture
5. Ambil wajah depan
6. Ambil wajah kanan
7. Ambil wajah kiri
8. Sistem validasi embedding wajah
9. User masuk ke halaman utama

### 11.2 Flow Login & Absen
1. User login
2. User masuk ke dashboard
3. Sistem mengecek status hari ini
4. Jika belum absen masuk, tombol absen masuk aktif
5. User klik absen masuk
6. Sistem buka kamera
7. Capture wajah
8. Cek lokasi dan geofence
9. Simpan attendance
10. User masuk ke halaman utama kembali

### 11.3 Flow Pulang
1. User pilih absen pulang
2. Camera aktif dan face match
3. Validasi lokasi
4. Simpan data pulang
5. Tampilkan status kerja hari ini

## 12. Halaman-Halaman yang Harus Ada
### 12.1 Halaman Umum
- Splash screen
- Login
- Register
- Forgot password

### 12.2 Halaman User
- Dashboard
- Absen
- Riwayat absensi
- Kamera wajah absensi
- Patrol
- Lokasi kerja
- Profil
- Pengaturan
- Izin

### 12.3 Halaman Admin
- Dashboard admin
- Kelola user
- Kelola lokasi
- Kelola shift
- Kelola izin
- Laporan absensi
- Auditor log
- Kelola patrol checkpoint

## 13. Requirement UI / UX
### 13.1 UI Prioritas
- Sederhana, cepat, jelas
- Bottom navigation nyaman untuk mobile
- Tombol absensi besar dan sangat jelas
- Notifikasi status jelas: sukses, gagal, terlambat, izin
- Kamera face capture harus mudah dipahami

### 13.2 UX Prioritas
- Proses onboarding face registration tidak terlalu panjang
- Ketika user berhasil register, otomatis lanjut ke halaman utama
- Riwayat absensi tampil kronologis
- Pengaturan mudah diakses dari profile

## 14. Data Model Inti
Berdasarkan `schema.sql`, model utama adalah:
- `users`
- `locations`
- `attendance`
- `patrol_checkpoints`
- `patrol_logs`
- `izin`
- `settings`

## 15. Kebutuhan Teknis
### 15.1 Backend
- Laravel + PostgreSQL
- Supabase-ready sesuai desain SQL
- Auth system untuk login/register
- Sanctum atau auth session untuk API mobile
- PostGIS untuk geofence
- Vector embedding untuk face recognition

### 15.2 Frontend
- Mobile-first UI
- Bottom navigation
- Camera handling
- Geolocation access
- Notification system
- Real-time status update

## 16. Prioritas Implementasi
### Prioritas P1 (Wajib pertama)
- Login & register
- Face onboarding 3 sudut
- Absensi masuk/pulang dengan camera & geofence
- Riwayat absensi
- Profile
- Pengaturan dasar

### Prioritas P2
- Bottom navigation
- Lokasi management
- Izin
- Patrol
- Notifikasi

### Prioritas P3
- Laporan admin
- Audit log
- Shift management
- Analitik absensi

## 17. Acceptance Criteria
### Login/Register
- User dapat register baru
- NIK unik
- Wajib mengisi kode lokasi
- User gagal register jika data tidak valid

### Face Enrollment
- User dapat capture 3 sudut wajah
- Data wajah disimpan dengan embedding
- Setelah selesai, status face_registered = true

### Absensi
- User dapat absen masuk/pulang
- System memvalidasi geofence
- System memvalidasi wajah
- Absensi tersimpan ke database

### Riwayat
- User bisa melihat data absensi sebelumnya
- Data disusun berdasarkan tanggal dan status

### Pengaturan
- User dapat mengubah profil, password, dan preferensi
- Admin dapat mengubah jam masuk/pulang dan toleransi

## 18. Kesimpulan
Aplikasi ini merupakan aplikasi absensi dan patroli keamanan yang menggabungkan:
- autentikasi user
- geofence lokasi
- face recognition
- aplikasi mobile-friendly
- manajemen admin dan lokasi
- pengaturan kerja lengkap

Fitur yang paling penting untuk diprioritaskan adalah:
1. register + onboarding wajah
2. absensi masuk/pulang via camera + geofence
3. riwayat absensi
4. profil + pengaturan
5. bottom navigation
6. lokasi / patrol / admin management

## 19. Rencana Implementasi Selanjutnya
Tahap berikutnya yang disarankan:
1. Buat API auth + register + login
2. Buat onboarding wajah 3 foto
3. Buat dashboard user
4. Buat absensi masuk/pulang + kamera + geofence
5. Buat riwayat absensi
6. Buat profile + settings
7. Buat admin panel manajemen lokasi & jadwal
8. Buat patrol checkpoint & reporting

Dokumen ini dapat dijadikan baseline untuk pembuatan aplikasi frontend dan backend berikutnya.
