# Kredensial Karyawan IndiSmart

## ⚠️ INFORMASI PENTING
**Hanya email dan password ini yang bisa diakses untuk bagian karyawan!**

## 🔐 Kredensial Login Karyawan

### Email: `karyawan@telkom.co.id`
### Password: `Ped123*`
### Role: Karyawan (Staff)

---

## 📋 Cara Login Karyawan

1. **Buka halaman login** di `/login`
2. **Masukkan email**: `karyawan@telkom.co.id`
3. **Masukkan password**: `Ped123*`
4. **Pilih role**: "Karyawan" dari dropdown
5. **Klik tombol "Masuk"**

---

## 🚫 Yang TIDAK Bisa Dilakukan

- ❌ **Tidak bisa register** sebagai karyawan
- ❌ **Tidak bisa ganti email** karyawan
- ❌ **Tidak bisa buat akun karyawan baru**
- ❌ **Tidak bisa reset password** sendiri

---

## 🔒 Keamanan Sistem

- ✅ **Hanya 1 email karyawan** yang diizinkan
- ✅ **Password kompleks** dengan karakter khusus
- ✅ **Middleware proteksi** mencegah registrasi karyawan
- ✅ **Validasi role** di level controller dan database
- ✅ **Kredensial tersembunyi** - hanya muncul saat memilih role "Karyawan"
- ✅ **Form registrasi aman** - langsung set sebagai Mitra tanpa opsi lain
- ✅ **Validasi login ketat** - Mitra tidak bisa akses login karyawan
- ✅ **Email restriction** - Hanya karyawan@telkom.co.id yang bisa login sebagai karyawan
- ✅ **Audit trail** - Semua aktivitas login tercatat untuk monitoring

---

## 🆘 Jika Ada Masalah

### Lupa Password
- Klik link "Lupa password?" di halaman login
- Masukkan email: `karyawan@telkom.co.id`
- Sistem akan generate token reset password
- Cek log Laravel untuk token (dalam development)
- Atau hubungi administrator untuk reset manual ke `Ped123*`

### Email Tidak Bisa Diakses
Pastikan menggunakan email yang benar: `karyawan@telkom.co.id`

### Role Tidak Muncul
Pastikan memilih "Karyawan" dari dropdown "Masuk Sebagai"

### Error "Email Tidak Memiliki Akses sebagai Karyawan"
- Pastikan menggunakan email: `karyawan@telkom.co.id`
- Email mitra tidak bisa digunakan untuk login sebagai karyawan
- Hanya akun karyawan yang bisa akses role karyawan

---

## 📱 Informasi Tambahan

- **Nama User**: Karyawan Indismart
- **Status**: Aktif
- **Hak Akses**: Full access untuk review dokumen dan manajemen mitra
- **Dashboard**: `/staff/dashboard`

---

## 🔄 Update Kredensial

Jika perlu update kredensial, gunakan perintah:

```bash
php artisan tinker

# Update password
$user = App\Models\User::where('email', 'karyawan@telkom.co.id')->first();
$user->update(['password' => Hash::make('password_baru')]);

# Update email
$user->update(['email' => 'email_baru@telkom.co.id']);
```

---

**⚠️ JANGAN BAGIKAN KREDENSIAL INI KEPADA ORANG LAIN!**
**⚠️ GUNAKAN HANYA UNTUK AKSES SISTEM INDIHOME!**
