# Perbaikan Validasi Upload File

## Perubahan yang Dilakukan

Validasi upload file telah diubah untuk hanya menerima format dokumen office dan PDF, menghilangkan format gambar.

### Format File yang Diizinkan
- ✅ **PDF** (.pdf)
- ✅ **Microsoft Word** (.doc, .docx)
- ✅ **Microsoft Excel** (.xls, .xlsx)
- ✅ **Microsoft PowerPoint** (.ppt, .pptx)

### Format File yang Dihapus
- ❌ **Gambar** (.jpg, .jpeg, .png)

## File yang Diperbarui

### 1. Controller - `app/Http/Controllers/DokumenController.php`

**Method `store()`:**
```php
// Sebelum
'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',

// Sesudah
'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
```

**Method `update()`:**
```php
// Sebelum
'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',

// Sesudah
'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
```

### 2. View - `resources/views/dokumen/create.blade.php`

**Input file:**
```html
<!-- Sebelum -->
<input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
<div class="form-text">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG (Max: 10MB)</div>

<!-- Sesudah -->
<input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
<div class="form-text">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (Max: 10MB)</div>
```

### 3. View - `resources/views/dokumen/edit.blade.php`

**Input file:**
```html
<!-- Sebelum -->
<input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
<div class="form-text">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG (Max: 10MB)</div>

<!-- Sesudah -->
<input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
<div class="form-text">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (Max: 10MB)</div>
```

## Alasan Perubahan

1. **Fokus pada Dokumen**: Sistem ini dirancang untuk mengelola dokumen proyek telekomunikasi, bukan gambar
2. **Keamanan**: Mengurangi risiko upload file berbahaya dengan membatasi format yang diizinkan
3. **Konsistensi**: Memastikan semua file yang diupload adalah dokumen yang relevan dengan proyek
4. **Ukuran File**: Format dokumen office dan PDF lebih efisien untuk penyimpanan dan pengelolaan

## Validasi yang Berlaku

- **Ukuran maksimal**: 10MB per file
- **Format yang diizinkan**: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX
- **Validasi server-side**: Laravel validation rules
- **Validasi client-side**: HTML5 accept attribute

## Testing

Untuk memastikan perubahan berfungsi dengan baik:

1. **Test format yang diizinkan**:
   - Upload file PDF ✅
   - Upload file DOC/DOCX ✅
   - Upload file XLS/XLSX ✅
   - Upload file PPT/PPTX ✅

2. **Test format yang ditolak**:
   - Upload file JPG/JPEG/PNG ❌
   - Upload file TXT ❌
   - Upload file ZIP ❌

3. **Test ukuran file**:
   - Upload file < 10MB ✅
   - Upload file > 10MB ❌

## Catatan

- File yang sudah diupload sebelumnya dengan format gambar tetap bisa diakses
- Perubahan ini hanya berlaku untuk upload file baru
- Jika ada kebutuhan untuk upload gambar di masa depan, bisa ditambahkan kembali dengan pertimbangan keamanan yang tepat
