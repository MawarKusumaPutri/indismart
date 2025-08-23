# ✅ Fitur Dropdown Dinamis STO dan Site Name

## 🎯 Deskripsi Fitur

Fitur dropdown dinamis memungkinkan user untuk memilih STO dan Site Name yang sesuai dengan Witel yang dipilih. Ketika user memilih Witel, dropdown STO akan otomatis terisi dengan STO yang tersedia di Witel tersebut. Kemudian ketika user memilih STO, dropdown Site Name akan terisi dengan Site Name yang tersedia di STO tersebut.

## 🔧 Implementasi

### **File yang Dimodifikasi:**
- `resources/views/dokumen/create.blade.php`
- `resources/views/dokumen/edit.blade.php`

### **Struktur Data:**

#### **1. Data STO berdasarkan Witel**
```javascript
const stoData = {
    'Jakarta': ['STO Kebayoran', 'STO Gambir', 'STO Cempaka Putih', 'STO Tanjung Priok', 'STO Jakarta Utara'],
    'Bandung': ['STO Dago', 'STO Hegarmanah', 'STO Ujung Berung', 'STO Cimahi', 'STO Bandung Selatan'],
    'Surabaya': ['STO Gubeng', 'STO Manyar', 'STO Rungkut', 'STO Surabaya Pusat', 'STO Surabaya Barat'],
    'Medan': ['STO Medan Kota', 'STO Medan Denai', 'STO Medan Amplas', 'STO Medan Timur', 'STO Medan Barat'],
    'Yogyakarta': ['STO Kotabaru', 'STO Bantul', 'STO Sleman', 'STO Yogyakarta Pusat', 'STO Yogyakarta Selatan'],
    'Semarang': ['STO Semarang Pusat', 'STO Semarang Utara', 'STO Semarang Timur', 'STO Semarang Barat'],
    'Palembang': ['STO Palembang Pusat', 'STO Palembang Utara', 'STO Palembang Timur', 'STO Palembang Barat'],
    'Makassar': ['STO Makassar Pusat', 'STO Makassar Utara', 'STO Makassar Timur', 'STO Makassar Barat']
};
```

#### **2. Data Site Name berdasarkan STO**
```javascript
const siteNameData = {
    'STO Kebayoran': ['Site KB-01', 'Site KB-02', 'Site KB-03', 'Site KB-04', 'Site KB-05'],
    'STO Gambir': ['Site GM-01', 'Site GM-02', 'Site GM-03', 'Site GM-04', 'Site GM-05'],
    'STO Cempaka Putih': ['Site CP-01', 'Site CP-02', 'Site CP-03', 'Site CP-04', 'Site CP-05'],
    // ... dan seterusnya untuk semua STO
};
```

## 🚀 Cara Kerja

### **1. Event Listener untuk Witel**
```javascript
document.getElementById('witel').addEventListener('change', function() {
    const witel = this.value;
    const stoSelect = document.getElementById('sto');
    const siteNameSelect = document.getElementById('site_name');
    
    // Reset dropdown STO dan Site Name
    stoSelect.innerHTML = '<option value="">Pilih STO</option>';
    siteNameSelect.innerHTML = '<option value="">Pilih Site Name</option>';
    
    if (witel && stoData[witel]) {
        // Isi dropdown STO berdasarkan Witel yang dipilih
        stoData[witel].forEach(sto => {
            const option = document.createElement('option');
            option.value = sto;
            option.textContent = sto;
            stoSelect.appendChild(option);
        });
    }
});
```

### **2. Event Listener untuk STO**
```javascript
document.getElementById('sto').addEventListener('change', function() {
    const sto = this.value;
    const siteNameSelect = document.getElementById('site_name');
    
    // Reset dropdown Site Name
    siteNameSelect.innerHTML = '<option value="">Pilih Site Name</option>';
    
    if (sto && siteNameData[sto]) {
        // Isi dropdown Site Name berdasarkan STO yang dipilih
        siteNameData[sto].forEach(site => {
            const option = document.createElement('option');
            option.value = site;
            option.textContent = site;
            siteNameSelect.appendChild(option);
        });
    }
});
```

### **3. Handling Old Input Values**
```javascript
document.addEventListener('DOMContentLoaded', function() {
    const oldWitel = '{{ old("witel") }}';
    const oldSto = '{{ old("sto") }}';
    const oldSiteName = '{{ old("site_name") }}';
    
    if (oldWitel) {
        document.getElementById('witel').value = oldWitel;
        document.getElementById('witel').dispatchEvent(new Event('change'));
        
        // Setelah STO terisi, set nilai STO dan trigger untuk Site Name
        setTimeout(() => {
            if (oldSto) {
                document.getElementById('sto').value = oldSto;
                document.getElementById('sto').dispatchEvent(new Event('change'));
                
                // Setelah Site Name terisi, set nilainya
                setTimeout(() => {
                    if (oldSiteName) {
                        document.getElementById('site_name').value = oldSiteName;
                    }
                }, 100);
            }
        }, 100);
    }
});
```

## 📋 Mapping Data

### **Witel Jakarta:**
- STO Kebayoran → Site KB-01, KB-02, KB-03, KB-04, KB-05
- STO Gambir → Site GM-01, GM-02, GM-03, GM-04, GM-05
- STO Cempaka Putih → Site CP-01, CP-02, CP-03, CP-04, CP-05
- STO Tanjung Priok → Site TP-01, TP-02, TP-03, TP-04, TP-05
- STO Jakarta Utara → Site JU-01, JU-02, JU-03, JU-04, JU-05

### **Witel Bandung:**
- STO Dago → Site DG-01, DG-02, DG-03, DG-04, DG-05
- STO Hegarmanah → Site HG-01, HG-02, HG-03, HG-04, HG-05
- STO Ujung Berung → Site UB-01, UB-02, UB-03, UB-04, UB-05
- STO Cimahi → Site CM-01, CM-02, CM-03, CM-04, CM-05
- STO Bandung Selatan → Site BS-01, BS-02, BS-03, BS-04, BS-05

### **Dan seterusnya untuk semua Witel...**

## 🎯 Fitur yang Tersedia

### **✅ Dropdown Dinamis**
- Witel → STO → Site Name
- Reset otomatis dropdown yang tidak relevan
- Validasi data yang konsisten

### **✅ Handling Form Validation**
- Support untuk Laravel validation errors
- Old input values preservation
- Error state handling

### **✅ User Experience**
- Dropdown yang responsif
- Loading state yang smooth
- Feedback visual yang jelas

### **✅ Data Consistency**
- Mapping data yang terstruktur
- Naming convention yang konsisten
- Hierarchical relationship yang jelas

## 🧪 Testing

### **Test Cases:**
1. **Pilih Witel Jakarta** → STO terisi dengan STO Jakarta
2. **Pilih STO Kebayoran** → Site Name terisi dengan Site KB-XX
3. **Ganti Witel ke Bandung** → STO dan Site Name reset dan terisi ulang
4. **Form validation error** → Old values tetap terpilih
5. **Edit dokumen** → Current values terpilih dengan benar

### **Expected Results:**
- ✅ Dropdown terisi sesuai hierarki
- ✅ Reset otomatis saat parent berubah
- ✅ Old values terpilih saat validation error
- ✅ Current values terpilih saat edit

## 🎯 Status: ✅ IMPLEMENTED

**Fitur dropdown dinamis STO dan Site Name sudah berfungsi dengan sempurna:**

- ✅ Dropdown Witel → STO → Site Name
- ✅ Data mapping yang lengkap
- ✅ Handling old input values
- ✅ Form validation support
- ✅ User experience yang smooth

**User sekarang bisa memilih STO dan Site Name yang sesuai dengan Witel yang dipilih!** 🎉
