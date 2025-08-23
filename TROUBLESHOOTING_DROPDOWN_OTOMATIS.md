# 🔧 Troubleshooting Dropdown Otomatis Status Implementasi → Jenis Dokumen

## 🚨 Masalah yang Ditemukan

**Gejala**: Dropdown Jenis Dokumen tidak terisi otomatis saat Status Implementasi dipilih.

## 🔍 Langkah-langkah Troubleshooting

### **1. Periksa Console Browser**

1. Buka Developer Tools (F12)
2. Pilih tab **Console**
3. Refresh halaman
4. Pilih Status Implementasi
5. Perhatikan pesan error atau log

### **2. Periksa Element HTML**

Pastikan struktur HTML sudah benar:

```html
<!-- Status Implementasi -->
<select id="status_implementasi" name="status_implementasi">
    <option value="">Pilih Status</option>
    <option value="inisiasi">Inisiasi</option>
    <option value="planning">Planning</option>
    <option value="executing">Executing</option>
    <option value="controlling">Controlling</option>
    <option value="closing">Closing</option>
</select>

<!-- Jenis Dokumen -->
<select id="jenis_dokumen" name="jenis_dokumen">
    <option value="">Pilih Jenis Dokumen</option>
</select>
```

### **3. Periksa JavaScript Data**

Pastikan data mapping sudah benar:

```javascript
const jenisDokumenData = {
    'inisiasi': [
        'Dokumen Kontrak Harga Satuan',
        'Dokumen Surat Pesanan'
    ],
    'planning': [
        'Berita Acara Design Review Meeting',
        'As Planned Drawing',
        'Rencana Anggaran Belanja',
        'Lainnya (Eviden Pendukung)'
    ],
    'executing': [
        'Berita Acara Penyelesaian Pekerjaan',
        'Berita Acara Uji Fungsi',
        'Lampiran Hasil Uji Fungsi',
        'Lainnya (Eviden Pendukung)'
    ],
    'controlling': [
        'Berita Acara Uji Terima',
        'Lampiran Hasil Uji Terima',
        'As Built Drawing Uji Terima'
    ],
    'closing': [
        'Berita Acara Rekonsiliasi',
        'Lampiran BoQ Hasil Rekonsiliasi',
        'Berita Acara Serah Terima'
    ]
};
```

### **4. Test Manual di Console**

Buka console browser dan jalankan:

```javascript
// Test 1: Periksa apakah data tersedia
console.log('jenisDokumenData:', jenisDokumenData);

// Test 2: Periksa apakah element ada
console.log('status_implementasi element:', document.getElementById('status_implementasi'));
console.log('jenis_dokumen element:', document.getElementById('jenis_dokumen'));

// Test 3: Test fungsi populateJenisDokumen
populateJenisDokumen('executing');

// Test 4: Test event listener
document.getElementById('status_implementasi').addEventListener('change', function() {
    console.log('Status changed to:', this.value);
    populateJenisDokumen(this.value);
});
```

## 🛠️ Solusi yang Diterapkan

### **1. Enhanced Debugging**

```javascript
// Fungsi dengan debugging yang lebih baik
function populateJenisDokumen(statusImplementasi) {
    console.log('🔧 populateJenisDokumen called with:', statusImplementasi);
    
    const jenisDokumenSelect = document.getElementById('jenis_dokumen');
    
    if (!jenisDokumenSelect) {
        console.error('❌ Jenis Dokumen element not found!');
        alert('Error: Jenis Dokumen element not found!');
        return;
    }
    
    // Reset dropdown
    jenisDokumenSelect.innerHTML = '<option value="">Pilih Jenis Dokumen</option>';
    
    if (statusImplementasi && jenisDokumenData[statusImplementasi]) {
        // Isi dropdown
        jenisDokumenData[statusImplementasi].forEach((jenis, index) => {
            const option = document.createElement('option');
            option.value = jenis;
            option.textContent = jenis;
            jenisDokumenSelect.appendChild(option);
            console.log(`✅ Added option ${index + 1}:`, jenis);
        });
        
        // Alert sukses untuk debugging
        alert(`Dropdown berhasil diisi untuk status: ${statusImplementasi}\nOpsi: ${jenisDokumenData[statusImplementasi].join(', ')}`);
    } else {
        alert(`Error: Status "${statusImplementasi}" not found in data!`);
    }
}
```

### **2. Auto-test Event Listener**

```javascript
// Event listener dengan auto-test
const statusSelect = document.getElementById('status_implementasi');
if (statusSelect) {
    statusSelect.addEventListener('change', function() {
        const selectedStatus = this.value;
        console.log('🔄 Status Implementasi changed to:', selectedStatus);
        populateJenisDokumen(selectedStatus);
    });
    
    // Auto-test setelah 2 detik
    setTimeout(() => {
        console.log('🧪 Simulating change event for testing...');
        statusSelect.value = 'executing';
        statusSelect.dispatchEvent(new Event('change'));
    }, 2000);
} else {
    alert('Error: Status Implementasi element not found!');
}
```

## 🧪 File Test

Gunakan file `test_dropdown_automatic.html` untuk testing:

1. Buka file di browser
2. Pilih Status Implementasi
3. Perhatikan console output
4. Gunakan tombol test untuk verifikasi

## 🔧 Kemungkinan Penyebab Masalah

### **1. JavaScript Error**
- Syntax error di JavaScript
- Element tidak ditemukan
- Data tidak terdefinisi

### **2. Timing Issue**
- JavaScript dijalankan sebelum DOM siap
- Event listener tidak terpasang dengan benar

### **3. CSS/Display Issue**
- Dropdown tersembunyi oleh CSS
- Z-index masalah
- Overflow hidden

### **4. Browser Compatibility**
- Browser tidak mendukung JavaScript ES6
- Ad blocker memblokir script

## ✅ Checklist Verifikasi

- [ ] Console browser tidak menampilkan error
- [ ] Element `status_implementasi` ditemukan
- [ ] Element `jenis_dokumen` ditemukan
- [ ] Data `jenisDokumenData` terdefinisi
- [ ] Event listener terpasang dengan benar
- [ ] Fungsi `populateJenisDokumen` berjalan
- [ ] Dropdown terisi dengan opsi yang benar

## 🚀 Langkah Selanjutnya

1. **Test di browser berbeda**
2. **Periksa network tab** untuk error loading
3. **Disable ad blocker** sementara
4. **Clear browser cache**
5. **Test di mode incognito**

## 📞 Support

Jika masalah masih berlanjut:

1. Screenshot console error
2. Screenshot network tab
3. Informasi browser dan versi
4. Langkah reproduksi yang detail
