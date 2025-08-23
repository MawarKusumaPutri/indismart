# Fitur Upload Foto - Dokumentasi Lengkap

## Overview

Fitur upload foto memungkinkan mitra untuk mengupload minimal 3 foto untuk setiap dokumen yang dibuat. Foto-foto ini akan menjadi bukti visual dari proyek yang sedang dikerjakan.

## Fitur Utama

### ✅ **Validasi Foto**
- **Minimal**: 3 foto per dokumen
- **Maksimal**: 10 foto per dokumen
- **Format**: JPG, JPEG, PNG
- **Ukuran**: Maksimal 5MB per foto
- **Validasi**: Client-side dan server-side

### ✅ **Interface Upload**
- **Drag & Drop**: Bisa drag foto langsung ke area upload
- **Click to Select**: Klik area upload untuk memilih file
- **Preview**: Preview foto sebelum upload
- **Caption**: Bisa menambahkan caption untuk setiap foto
- **Remove**: Bisa menghapus foto yang sudah dipilih

### ✅ **Manajemen Foto**
- **Ordering**: Foto diurutkan berdasarkan urutan upload
- **Caption**: Setiap foto bisa memiliki caption
- **Storage**: Foto disimpan di folder `storage/app/public/fotos/`
- **Database**: Data foto disimpan di tabel `fotos`

## Struktur Database

### Tabel `fotos`
```sql
CREATE TABLE fotos (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    dokumen_id BIGINT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    caption VARCHAR(255) NULL,
    `order` INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (dokumen_id) REFERENCES dokumen(id) ON DELETE CASCADE
);
```

### Relasi
- `Dokumen` has many `Foto`
- `Foto` belongs to `Dokumen`

## File yang Diperbarui

### 1. **Model**
- `app/Models/Foto.php` - Model untuk foto
- `app/Models/Dokumen.php` - Menambahkan relasi dengan foto

### 2. **Controller**
- `app/Http/Controllers/FotoController.php` - Controller untuk manajemen foto
- `app/Http/Controllers/DokumenController.php` - Menambahkan handling foto upload

### 3. **View**
- `resources/views/dokumen/create.blade.php` - Menambahkan form upload foto

### 4. **Routes**
- `routes/web.php` - Menambahkan route untuk foto

### 5. **Migration**
- `database/migrations/2025_08_23_125204_create_fotos_table.php`

## Cara Penggunaan

### 1. **Upload Foto**
1. Buka halaman "Tambah Dokumen Baru"
2. Isi semua field dokumen yang diperlukan
3. Di bagian "Upload Foto":
   - Klik area upload atau drag & drop foto
   - Pilih minimal 3 foto (maksimal 10 foto)
   - Tambahkan caption untuk setiap foto (opsional)
   - Preview foto sebelum upload
4. Klik "Simpan Dokumen"
5. Dokumen dan foto akan diupload bersamaan

### 2. **Validasi**
- Sistem akan memvalidasi:
  - Minimal 3 foto
  - Format file (JPG, JPEG, PNG)
  - Ukuran file (maksimal 5MB)
  - Jumlah foto (maksimal 10)

### 3. **Preview**
- Foto yang dipilih akan ditampilkan dalam grid
- Setiap foto bisa dihapus dengan tombol X
- Caption bisa diisi untuk setiap foto
- Status validasi ditampilkan di bawah preview

## API Endpoints

### Upload Foto
```
POST /dokumen/{dokumen}/fotos
```

### Get Foto
```
GET /dokumen/{dokumen}/fotos
```

### Delete Foto
```
DELETE /fotos/{foto}
```

### Update Order Foto
```
PUT /dokumen/{dokumen}/fotos/order
```

## Keamanan

### ✅ **Validasi File**
- Validasi MIME type
- Validasi ukuran file
- Validasi ekstensi file
- Sanitasi nama file

### ✅ **Akses Kontrol**
- Hanya mitra yang bisa upload foto
- Mitra hanya bisa upload foto untuk dokumennya sendiri
- Staff bisa melihat semua foto

### ✅ **Storage**
- File disimpan di folder yang aman
- Nama file di-hash untuk keamanan
- Backup dan recovery tersedia

## Error Handling

### Client-side Errors
- Format file tidak valid
- Ukuran file terlalu besar
- Jumlah foto tidak memenuhi syarat
- Network error

### Server-side Errors
- Validasi gagal
- Storage error
- Database error
- Permission error

## Performance

### ✅ **Optimization**
- File compression untuk preview
- Lazy loading untuk foto
- Caching untuk foto yang sering diakses
- Batch upload untuk multiple foto

### ✅ **Storage**
- Foto disimpan di public storage
- CDN ready untuk production
- Backup strategy tersedia

## Testing

### Manual Testing
1. **Upload 2 foto** - Harus error (minimal 3)
2. **Upload 11 foto** - Harus error (maksimal 10)
3. **Upload file non-gambar** - Harus error
4. **Upload file > 5MB** - Harus error
5. **Upload 3-10 foto valid** - Harus berhasil

### Automated Testing
- Unit tests untuk model Foto
- Feature tests untuk upload foto
- Integration tests untuk API

## Troubleshooting

### Common Issues
1. **Foto tidak muncul**: Cek storage link
2. **Upload gagal**: Cek permission folder storage
3. **Preview error**: Cek JavaScript console
4. **Validasi error**: Cek format dan ukuran file

### Solutions
1. **Storage link**: `php artisan storage:link`
2. **Permission**: `chmod -R 775 storage/`
3. **Clear cache**: `php artisan cache:clear`
4. **Reset database**: `php artisan migrate:fresh`

## Future Enhancements

### Planned Features
- **Image compression**: Otomatis compress foto
- **Watermark**: Tambah watermark ke foto
- **Gallery view**: Tampilan gallery yang lebih baik
- **Bulk operations**: Operasi massal untuk foto
- **Advanced filtering**: Filter foto berdasarkan metadata

### Technical Improvements
- **CDN integration**: Integrasi dengan CDN
- **Image optimization**: Optimasi gambar otomatis
- **Progressive loading**: Loading progresif untuk foto besar
- **Offline support**: Upload offline dengan queue

## Support

Untuk bantuan teknis atau pertanyaan tentang fitur upload foto, silakan hubungi tim development atau buat issue di repository.
