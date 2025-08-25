# ✅ Update Jenis Proyek Dropdown

## 🎯 Tujuan

Menambahkan 3 jenis proyek baru ke dalam dropdown "Jenis Proyek" di seluruh aplikasi:
- **Recovery**
- **Preventif** 
- **Relokasi**

## 📋 Jenis Proyek yang Tersedia

### **Jenis Proyek yang Sudah Ada:**
1. Instalasi Baru
2. Migrasi
3. Upgrade
4. Maintenance
5. Troubleshooting
6. Survey
7. Audit
8. Lainnya

### **Jenis Proyek Baru yang Ditambahkan:**
9. **Recovery** - Untuk proyek pemulihan sistem atau jaringan
10. **Preventif** - Untuk proyek pencegahan dan perawatan preventif
11. **Relokasi** - Untuk proyek pemindahan lokasi peralatan atau sistem

## 🔧 File yang Diupdate

### **1. Dokumen Management**
- `resources/views/dokumen/create.blade.php` - Form pembuatan dokumen
- `resources/views/dokumen/edit.blade.php` - Form edit dokumen
- `resources/views/dokumen/index.blade.php` - Filter pencarian dokumen

### **2. Review Management**
- `resources/views/reviews/index.blade.php` - Filter pencarian review

### **3. Mitra Dashboard**
- `resources/views/mitra/dashboard.blade.php` - Filter proyek di dashboard mitra

## 📝 Implementasi

### **Dropdown Standard (Dokumen & Review)**
```html
<select class="form-select" id="jenis_proyek" name="jenis_proyek">
    <option value="">Pilih Jenis Proyek</option>
    <option value="Instalasi Baru">Instalasi Baru</option>
    <option value="Migrasi">Migrasi</option>
    <option value="Upgrade">Upgrade</option>
    <option value="Maintenance">Maintenance</option>
    <option value="Troubleshooting">Troubleshooting</option>
    <option value="Survey">Survey</option>
    <option value="Audit">Audit</option>
    <option value="Recovery">Recovery</option>
    <option value="Preventif">Preventif</option>
    <option value="Relokasi">Relokasi</option>
    <option value="Lainnya">Lainnya</option>
</select>
```

### **Mitra Dashboard Filter**
```html
<select class="form-select" id="jenisProjek">
    <option value="" selected>Semua Jenis</option>
    <option value="fiber">Pemasangan Fiber Optik</option>
    <option value="upgrade">Upgrade Jaringan</option>
    <option value="instalasi">Instalasi BTS</option>
    <option value="maintenance">Maintenance</option>
    <option value="recovery">Recovery</option>
    <option value="preventif">Preventif</option>
    <option value="relokasi">Relokasi</option>
</select>
```

## ✅ Status Implementasi

- [x] Dokumen Create Form
- [x] Dokumen Edit Form  
- [x] Dokumen Index Filter
- [x] Review Index Filter
- [x] Mitra Dashboard Filter

## 🎉 Hasil

Sekarang pengguna dapat memilih 3 jenis proyek baru (Recovery, Preventif, Relokasi) di semua dropdown "Jenis Proyek" di seluruh aplikasi. Perubahan ini konsisten di semua halaman yang menggunakan jenis proyek dropdown.
