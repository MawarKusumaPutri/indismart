// ========================================
// AUTOMATIC DROPDOWN: Status Implementasi → Jenis Dokumen
// ========================================

// Data mapping Status Implementasi → Jenis Dokumen
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

// Fungsi untuk mengisi dropdown Jenis Dokumen
function populateJenisDokumen(statusImplementasi) {
    console.log('🔧 populateJenisDokumen called with:', statusImplementasi);
    
    const jenisDokumenSelect = document.getElementById('jenis_dokumen');
    
    if (!jenisDokumenSelect) {
        console.error('❌ Jenis Dokumen element not found!');
        return;
    }
    
    console.log('✅ Jenis Dokumen element found, resetting dropdown...');
    
    // Reset dropdown Jenis Dokumen
    jenisDokumenSelect.innerHTML = '<option value="">Pilih Jenis Dokumen</option>';
    
    if (statusImplementasi && jenisDokumenData[statusImplementasi]) {
        console.log('✅ Data found for status:', statusImplementasi);
        
        // Isi dropdown Jenis Dokumen berdasarkan Status Implementasi yang dipilih
        jenisDokumenData[statusImplementasi].forEach((jenis, index) => {
            const option = document.createElement('option');
            option.value = jenis;
            option.textContent = jenis;
            jenisDokumenSelect.appendChild(option);
            console.log(`✅ Added option ${index + 1}:`, jenis);
        });
        
        console.log('🎉 Dropdown Jenis Dokumen berhasil diisi untuk Status Implementasi:', statusImplementasi);
    } else {
        console.log('❌ No data found for status:', statusImplementasi);
    }
}

// Fungsi untuk setup event listeners
function setupDropdownListeners() {
    console.log('🚀 Setting up dropdown listeners...');
    
    const statusSelect = document.getElementById('status_implementasi');
    if (statusSelect) {
        console.log('✅ Status Implementasi element found');
        
        // Event listener untuk perubahan Status Implementasi
        statusSelect.addEventListener('change', function() {
            const selectedStatus = this.value;
            console.log('🔄 Status Implementasi changed to:', selectedStatus);
            populateJenisDokumen(selectedStatus);
        });
        
        console.log('✅ Event listener untuk Status Implementasi berhasil ditambahkan');
    } else {
        console.error('❌ Status Implementasi element not found!');
    }
}

// Fungsi untuk auto-trigger jika ada nilai awal
function autoTriggerDropdown() {
    const statusSelect = document.getElementById('status_implementasi');
    if (statusSelect && statusSelect.value) {
        console.log('🔄 Auto-triggering populateJenisDokumen for existing value:', statusSelect.value);
        populateJenisDokumen(statusSelect.value);
    }
}

// Inisialisasi saat DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM Content Loaded - Initializing automatic dropdown');
    console.log('📊 Jenis Dokumen Data available:', jenisDokumenData);
    
    // Setup event listeners
    setupDropdownListeners();
    
    // Auto-trigger untuk nilai yang sudah ada
    setTimeout(autoTriggerDropdown, 100);
    
    console.log('✅ Automatic dropdown initialization complete');
});

// Export untuk penggunaan global
window.populateJenisDokumen = populateJenisDokumen;
window.setupDropdownListeners = setupDropdownListeners;
window.jenisDokumenData = jenisDokumenData;
