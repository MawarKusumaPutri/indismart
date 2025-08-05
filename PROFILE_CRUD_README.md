# CRUD Profile - SmartPED

Dokumentasi untuk sistem CRUD (Create, Read, Update, Delete) profile yang telah diimplementasikan di aplikasi SmartPED.

## Fitur yang Tersedia

### 1. Tampilan Profile (Read)
- **Route**: `GET /profile`
- **Controller**: `ProfileController@show`
- **View**: `resources/views/profile/show.blade.php`
- **Fitur**:
  - Menampilkan informasi lengkap user
  - Avatar profile (jika ada)
  - Informasi personal (nama, email, telepon, alamat, tanggal lahir, jenis kelamin)
  - Status role (mitra/staff)
  - Tanggal bergabung

### 2. Edit Profile (Update)
- **Route**: `GET /profile/edit` dan `PUT /profile`
- **Controller**: `ProfileController@edit` dan `ProfileController@update`
- **View**: `resources/views/profile/edit.blade.php`
- **Fitur**:
  - Form edit profile dengan validasi
  - Upload avatar dengan preview
  - Validasi file gambar (JPG, PNG, GIF, maksimal 2MB)
  - Hapus avatar yang ada

### 3. Ubah Password
- **Route**: `GET /profile/change-password` dan `PUT /profile/password`
- **Controller**: `ProfileController@changePassword` dan `ProfileController@updatePassword`
- **View**: `resources/views/profile/change-password.blade.php`
- **Fitur**:
  - Form ubah password dengan validasi
  - Validasi password saat ini
  - Konfirmasi password baru
  - Toggle visibility password
  - Tips password yang aman

### 4. Hapus Avatar (Delete)
- **Route**: `DELETE /profile/avatar`
- **Controller**: `ProfileController@deleteAvatar`
- **Fitur**:
  - Hapus avatar dari storage
  - Update database untuk menghapus referensi avatar

## Struktur Database

### Migration: `add_profile_fields_to_users_table`

Kolom baru yang ditambahkan ke tabel `users`:

```php
$table->string('phone')->nullable()->after('email');
$table->text('address')->nullable()->after('phone');
$table->string('avatar')->nullable()->after('address');
$table->date('birth_date')->nullable()->after('avatar');
$table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
```

### Model User

Field yang ditambahkan ke `$fillable`:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'phone',
    'address',
    'avatar',
    'birth_date',
    'gender',
];
```

## Routes

Semua route profile dilindungi dengan middleware `auth`:

```php
Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');
});
```

## Validasi

### Profile Update
- Nama: required, string, max 255 karakter
- Email: required, email, unique (kecuali user saat ini)
- Telepon: nullable, string, max 20 karakter
- Alamat: nullable, string, max 500 karakter
- Tanggal lahir: nullable, date, sebelum hari ini
- Jenis kelamin: nullable, enum (male/female)
- Avatar: nullable, image, mimes (jpeg,png,jpg,gif), max 2MB

### Password Update
- Password saat ini: required, current_password
- Password baru: required, string, min 8 karakter, confirmed

## Integrasi dengan Dashboard

Link ke profile telah ditambahkan di:
- Dashboard Mitra (`resources/views/mitra/dashboard.blade.php`)
- Dashboard Staff (`resources/views/staff/dashboard.blade.php`)

Menu dropdown user di navbar sekarang berisi:
- Profil (link ke `profile.show`)
- Edit Profil (link ke `profile.edit`)
- Ubah Password (link ke `profile.change-password`)

## File Storage

Avatar disimpan di:
- **Path**: `storage/app/public/avatars/`
- **URL**: `/storage/avatars/`
- **Symlink**: `public/storage` → `storage/app/public`

## Cara Penggunaan

### Untuk User
1. Login ke aplikasi
2. Klik nama user di navbar (dropdown)
3. Pilih "Profil" untuk melihat profile
4. Pilih "Edit Profil" untuk mengubah data
5. Pilih "Ubah Password" untuk mengubah password

### Untuk Developer
1. Jalankan migration: `php artisan migrate`
2. Buat symbolic link storage: `php artisan storage:link`
3. Pastikan folder `storage/app/public/avatars` memiliki permission write

## Keamanan

- Semua route dilindungi middleware `auth`
- Validasi file upload untuk mencegah upload file berbahaya
- Password di-hash menggunakan bcrypt
- Validasi email unik dengan pengecualian user saat ini
- Konfirmasi untuk aksi hapus avatar

## UI/UX Features

- Responsive design dengan Bootstrap 5
- Icon Font Awesome untuk visual yang menarik
- Preview avatar sebelum upload
- Toggle visibility untuk password
- Alert messages untuk feedback user
- Loading states dan validasi real-time
- Konfirmasi untuk aksi penting

## Troubleshooting

### Avatar tidak muncul
1. Pastikan symbolic link storage sudah dibuat
2. Cek permission folder `storage/app/public/avatars`
3. Pastikan file ada di lokasi yang benar

### Migration error
1. Rollback migration: `php artisan migrate:rollback`
2. Cek struktur tabel users
3. Jalankan ulang migration

### Upload file error
1. Cek permission folder storage
2. Pastikan file tidak melebihi 2MB
3. Cek format file (hanya JPG, PNG, GIF) 