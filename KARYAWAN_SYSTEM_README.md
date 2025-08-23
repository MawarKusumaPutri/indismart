# Sistem Karyawan IndiSmart

## Overview
Sistem ini telah dikonfigurasi agar **karyawan hanya bisa akses dengan 1 email saja** dan **tidak perlu register**. Karyawan hanya bisa login dengan akun yang sudah dibuat oleh administrator.

## Konfigurasi Sistem

### 1. User Karyawan Default
- **Email**: `karyawan@telkom.co.id`
- **Password**: `Ped123*`
- **Role**: `staff` (dalam database)
- **Nama**: Karyawan Indismart

### 2. Fitur Keamanan
- ✅ **Registrasi Karyawan Diblokir**: User tidak bisa register sebagai karyawan
- ✅ **Hanya 1 Email Karyawan**: Sistem hanya mengizinkan 1 user karyawan
- ✅ **Middleware Proteksi**: Mencegah akses ke halaman registrasi jika sudah ada karyawan
- ✅ **Validasi Role**: Mencegah registrasi dengan role 'staff'
- ✅ **Validasi Login Ketat**: Mitra tidak bisa login sebagai karyawan dan sebaliknya
- ✅ **Email Restriction**: Hanya karyawan@telkom.co.id yang bisa login sebagai karyawan
- ✅ **Audit Trail**: Log semua aktivitas login untuk monitoring keamanan

### 3. Alur Sistem

#### Untuk Karyawan:
1. **Login Saja**: Karyawan hanya perlu login dengan email dan password yang sudah ada
2. **Tidak Bisa Register**: Jika mencoba register sebagai karyawan, akan ditolak
3. **Akses Terbatas**: Hanya bisa login dengan akun yang sudah dibuat
4. **Kredensial Tersembunyi**: Email dan password hanya muncul saat memilih role "Karyawan"
5. **Email Restriction**: Hanya bisa login dengan email karyawan@telkom.co.id
6. **Role Protection**: Tidak bisa diakses oleh user mitra

#### Untuk Mitra:
1. **Bisa Register**: Mitra bisa register akun baru (langsung sebagai Mitra)
2. **Bisa Login**: Mitra bisa login dengan akun yang sudah dibuat
3. **Akses Penuh**: Bisa membuat dan mengelola dokumen
4. **Form Sederhana**: Tidak perlu pilih role, langsung ter-set sebagai Mitra

## File yang Dimodifikasi

### 1. **Database Seeder**
- `database/seeders/DefaultUserSeeder.php` - Membuat user karyawan default

### 2. **Controller**
- `app/Http/Controllers/AuthController.php` - Mencegah registrasi karyawan

### 3. **Middleware**
- `app/Http/Middleware/PreventKaryawanRegistration.php` - Middleware baru untuk mencegah registrasi karyawan

### 4. **Views**
- `resources/views/auth/login.blade.php` - Menampilkan email karyawan hanya ketika role "Karyawan" dipilih
- `resources/views/auth/register.blade.php` - Form registrasi langsung menampilkan "Mitra" tanpa dropdown

### 5. **Routes**
- `routes/web.php` - Menerapkan middleware pada route registrasi

### 6. **Kernel**
- `app/Http/Kernel.php` - Mendaftarkan middleware baru

### 7. **Controller (Updated)**
- `app/Http/Controllers/AuthController.php` - Validasi email karyawan langsung di controller

### 5. **Routes**
- `routes/web.php` - Menerapkan middleware pada route registrasi

### 6. **Kernel**
- `app/Http/Kernel.php` - Mendaftarkan middleware baru

## Cara Penggunaan

### 1. **Login Karyawan**
```
Email: karyawan@telkom.co.id
Password: Ped123*
Role: Karyawan
```

### 2. **Login Mitra**
```
Email: [email yang sudah register]
Password: [password yang sudah dibuat]
Role: Mitra
```

### 3. **Register Mitra Baru**
- Akses halaman `/register`
- Form otomatis set sebagai "Mitra"
- Isi data lengkap (nama, email, password)
- Submit form

## Keamanan

### 1. **Proteksi Registrasi Karyawan**
- Middleware `PreventKaryawanRegistration` mencegah registrasi karyawan
- Validasi di controller mencegah role 'staff' dalam form
- Form registrasi langsung set sebagai "Mitra" tanpa opsi lain
- Input hidden field memastikan role selalu 'mitra'

### 2. **Proteksi Login Karyawan**
- Validasi email langsung di controller untuk keamanan maksimal
- Hanya `karyawan@telkom.co.id` yang bisa login sebagai karyawan
- Email karyawan tidak bisa digunakan untuk login mitra
- Validasi role ganda: email + role database

### 3. **Fitur Lupa Password**
- Link "Lupa password?" yang bisa diklik
- Form request reset password via email
- Token reset password yang aman
- Halaman reset password dengan validasi
- Update password otomatis setelah reset

### 2. **Validasi Role**
- Hanya role 'mitra' yang diizinkan untuk registrasi
- Role 'staff' diblokir di level controller dan middleware

### 3. **Pesan Error yang Informatif**
- User mendapat pesan jelas mengapa registrasi karyawan ditolak
- Redirect otomatis ke halaman login jika sudah ada karyawan

## Troubleshooting

### 1. **Jika Karyawan Lupa Password**
- Hubungi administrator untuk reset password
- Atau gunakan fitur "Lupa Password" (jika tersedia)

### 2. **Jika Ingin Ganti Email Karyawan**
- Update langsung di database
- Atau buat user karyawan baru dan hapus yang lama

### 3. **Jika Sistem Error**
- Pastikan middleware sudah terdaftar di Kernel.php
- Pastikan seeder sudah dijalankan
- Cek log Laravel untuk error detail

## Maintenance

### 1. **Update Password Karyawan**
```bash
php artisan tinker
$user = App\Models\User::where('role', 'staff')->first();
$user->update(['password' => Hash::make('password_baru')]);
```

### 2. **Update Email Karyawan**
```bash
php artisan tinker
$user = App\Models\User::where('role', 'staff')->first();
$user->update(['email' => 'email_baru@telkom.co.id']);
```

### 3. **Reset Sistem Karyawan**
```bash
php artisan db:seed --class=DefaultUserSeeder
```

## Kesimpulan

Sistem ini sekarang aman dan hanya mengizinkan 1 user karyawan yang bisa login tanpa perlu register. Mitra tetap bisa register dan login seperti biasa. Semua fitur keamanan sudah diterapkan untuk mencegah penyalahgunaan sistem.
