// ========================================
// AUTOMATIC DROPDOWN: Witel → STO → Site Name
// ========================================

// Data STO berdasarkan Witel
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

// Data Site Name berdasarkan STO
const siteNameData = {
    'STO Kebayoran': ['Site KB-01', 'Site KB-02', 'Site KB-03', 'Site KB-04', 'Site KB-05'],
    'STO Gambir': ['Site GM-01', 'Site GM-02', 'Site GM-03', 'Site GM-04', 'Site GM-05'],
    'STO Cempaka Putih': ['Site CP-01', 'Site CP-02', 'Site CP-03', 'Site CP-04', 'Site CP-05'],
    'STO Tanjung Priok': ['Site TP-01', 'Site TP-02', 'Site TP-03', 'Site TP-04', 'Site TP-05'],
    'STO Jakarta Utara': ['Site JU-01', 'Site JU-02', 'Site JU-03', 'Site JU-04', 'Site JU-05'],
    'STO Dago': ['Site DG-01', 'Site DG-02', 'Site DG-03', 'Site DG-04', 'Site DG-05'],
    'STO Hegarmanah': ['Site HG-01', 'Site HG-02', 'Site HG-03', 'Site HG-04', 'Site HG-05'],
    'STO Ujung Berung': ['Site UB-01', 'Site UB-02', 'Site UB-03', 'Site UB-04', 'Site UB-05'],
    'STO Cimahi': ['Site CM-01', 'Site CM-02', 'Site CM-03', 'Site CM-04', 'Site CM-05'],
    'STO Bandung Selatan': ['Site BS-01', 'Site BS-02', 'Site BS-03', 'Site BS-04', 'Site BS-05'],
    'STO Gubeng': ['Site GB-01', 'Site GB-02', 'Site GB-03', 'Site GB-04', 'Site GB-05'],
    'STO Manyar': ['Site MY-01', 'Site MY-02', 'Site MY-03', 'Site MY-04', 'Site MY-05'],
    'STO Rungkut': ['Site RK-01', 'Site RK-02', 'Site RK-03', 'Site RK-04', 'Site RK-05'],
    'STO Surabaya Pusat': ['Site SP-01', 'Site SP-02', 'Site SP-03', 'Site SP-04', 'Site SP-05'],
    'STO Surabaya Barat': ['Site SB-01', 'Site SB-02', 'Site SB-03', 'Site SB-04', 'Site SB-05'],
    'STO Medan Kota': ['Site MK-01', 'Site MK-02', 'Site MK-03', 'Site MK-04', 'Site MK-05'],
    'STO Medan Denai': ['Site MD-01', 'Site MD-02', 'Site MD-03', 'Site MD-04', 'Site MD-05'],
    'STO Medan Amplas': ['Site MA-01', 'Site MA-02', 'Site MA-03', 'Site MA-04', 'Site MA-05'],
    'STO Medan Timur': ['Site MT-01', 'Site MT-02', 'Site MT-03', 'Site MT-04', 'Site MT-05'],
    'STO Medan Barat': ['Site MB-01', 'Site MB-02', 'Site MB-03', 'Site MB-04', 'Site MB-05'],
    'STO Kotabaru': ['Site KB-01', 'Site KB-02', 'Site KB-03', 'Site KB-04', 'Site KB-05'],
    'STO Bantul': ['Site BT-01', 'Site BT-02', 'Site BT-03', 'Site BT-04', 'Site BT-05'],
    'STO Sleman': ['Site SL-01', 'Site SL-02', 'Site SL-03', 'Site SL-04', 'Site SL-05'],
    'STO Yogyakarta Pusat': ['Site YP-01', 'Site YP-02', 'Site YP-03', 'Site YP-04', 'Site YP-05'],
    'STO Yogyakarta Selatan': ['Site YS-01', 'Site YS-02', 'Site YS-03', 'Site YS-04', 'Site YS-05'],
    'STO Semarang Pusat': ['Site SP-01', 'Site SP-02', 'Site SP-03', 'Site SP-04', 'Site SP-05'],
    'STO Semarang Utara': ['Site SU-01', 'Site SU-02', 'Site SU-03', 'Site SU-04', 'Site SU-05'],
    'STO Semarang Timur': ['Site ST-01', 'Site ST-02', 'Site ST-03', 'Site ST-04', 'Site ST-05'],
    'STO Semarang Barat': ['Site SB-01', 'Site SB-02', 'Site SB-03', 'Site SB-04', 'Site SB-05'],
    'STO Palembang Pusat': ['Site PP-01', 'Site PP-02', 'Site PP-03', 'Site PP-04', 'Site PP-05'],
    'STO Palembang Utara': ['Site PN-01', 'Site PN-02', 'Site PN-03', 'Site PN-04', 'Site PN-05'],
    'STO Palembang Timur': ['Site PT-01', 'Site PT-02', 'Site PT-03', 'Site PT-04', 'Site PT-05'],
    'STO Palembang Barat': ['Site PB-01', 'Site PB-02', 'Site PB-03', 'Site PB-04', 'Site PB-05'],
    'STO Makassar Pusat': ['Site MP-01', 'Site MP-02', 'Site MP-03', 'Site MP-04', 'Site MP-05'],
    'STO Makassar Utara': ['Site MN-01', 'Site MN-02', 'Site MN-03', 'Site MN-04', 'Site MN-05'],
    'STO Makassar Timur': ['Site MT-01', 'Site MT-02', 'Site MT-03', 'Site MT-04', 'Site MT-05'],
    'STO Makassar Barat': ['Site MB-01', 'Site MB-02', 'Site MB-03', 'Site MB-04', 'Site MB-05']
};

// Fungsi untuk mengisi dropdown STO berdasarkan Witel yang dipilih
function populateSTO(witel) {
    console.log('🔧 populateSTO called with:', witel);
    
    const stoSelect = document.getElementById('sto');
    const siteNameSelect = document.getElementById('site_name');
    
    if (!stoSelect) {
        console.error('❌ STO element not found!');
        alert('Error: STO element not found!');
        return;
    }
    
    if (!siteNameSelect) {
        console.error('❌ Site Name element not found!');
        alert('Error: Site Name element not found!');
        return;
    }
    
    console.log('✅ STO and Site Name elements found, resetting dropdowns...');
    
    // Reset dropdown STO
    stoSelect.innerHTML = '<option value="">Pilih STO</option>';
    
    // Reset dropdown Site Name
    siteNameSelect.innerHTML = '<option value="">Pilih Site Name</option>';
    
    console.log('📊 Populating STO for Witel:', witel);
    console.log('📋 Available data for this witel:', stoData[witel]);
    
    if (witel && stoData[witel]) {
        console.log('✅ Data found for witel:', witel);
        console.log('📝 Adding STO options...');
        
        // Isi dropdown STO berdasarkan Witel yang dipilih
        stoData[witel].forEach((sto, index) => {
            const option = document.createElement('option');
            option.value = sto;
            option.textContent = sto;
            stoSelect.appendChild(option);
            console.log(`✅ Added STO option ${index + 1}:`, sto);
        });
        
        console.log('📊 STO options added:', stoData[witel]);
        console.log('🔢 Total STO options in dropdown:', stoSelect.options.length);
        
        // Alert sukses untuk debugging
        alert(`STO dropdown berhasil diisi untuk Witel: ${witel}\nOpsi: ${stoData[witel].join(', ')}`);
        
        console.log('🎉 STO dropdown berhasil diisi untuk Witel:', witel);
    } else {
        console.log('❌ No data found for witel:', witel);
        console.log('📋 Available witels:', Object.keys(stoData));
        alert(`Error: Tidak ada data untuk Witel "${witel}"!`);
    }
}

// Fungsi untuk mengisi dropdown Site Name berdasarkan STO yang dipilih
function populateSiteName(sto) {
    console.log('🔧 populateSiteName called with:', sto);
    
    const siteNameSelect = document.getElementById('site_name');
    
    if (!siteNameSelect) {
        console.error('❌ Site Name element not found!');
        alert('Error: Site Name element not found!');
        return;
    }
    
    console.log('✅ Site Name element found, resetting dropdown...');
    
    // Reset dropdown Site Name
    siteNameSelect.innerHTML = '<option value="">Pilih Site Name</option>';
    
    console.log('📊 Populating Site Name for STO:', sto);
    console.log('📋 Available data for this STO:', siteNameData[sto]);
    
    if (sto && siteNameData[sto]) {
        console.log('✅ Data found for STO:', sto);
        console.log('📝 Adding Site Name options...');
        
        // Isi dropdown Site Name berdasarkan STO yang dipilih
        siteNameData[sto].forEach((site, index) => {
            const option = document.createElement('option');
            option.value = site;
            option.textContent = site;
            siteNameSelect.appendChild(option);
            console.log(`✅ Added Site Name option ${index + 1}:`, site);
        });
        
        console.log('📊 Site Name options added:', siteNameData[sto]);
        console.log('🔢 Total Site Name options in dropdown:', siteNameSelect.options.length);
        
        // Alert sukses untuk debugging
        alert(`Site Name dropdown berhasil diisi untuk STO: ${sto}\nOpsi: ${siteNameData[sto].join(', ')}`);
        
        console.log('🎉 Site Name dropdown berhasil diisi untuk STO:', sto);
    } else {
        console.log('❌ No data found for STO:', sto);
        console.log('📋 Available STOs:', Object.keys(siteNameData));
        alert(`Error: Tidak ada data untuk STO "${sto}"!`);
    }
}

// Fungsi untuk setup event listeners
function setupWitelStoSiteListeners() {
    console.log('🚀 Setting up Witel → STO → Site Name listeners...');
    
    // Event listener untuk Witel
    const witelSelect = document.getElementById('witel');
    if (witelSelect) {
        console.log('✅ Witel element found');
        witelSelect.addEventListener('change', function() {
            const selectedWitel = this.value;
            console.log('🔄 Witel changed to:', selectedWitel);
            populateSTO(selectedWitel);
        });
        console.log('✅ Event listener untuk Witel berhasil ditambahkan');
    } else {
        console.error('❌ Witel element not found!');
        alert('Error: Witel element not found!');
    }
    
    // Event listener untuk STO
    const stoSelect = document.getElementById('sto');
    if (stoSelect) {
        console.log('✅ STO element found');
        stoSelect.addEventListener('change', function() {
            const selectedSto = this.value;
            console.log('🔄 STO changed to:', selectedSto);
            populateSiteName(selectedSto);
        });
        console.log('✅ Event listener untuk STO berhasil ditambahkan');
    } else {
        console.error('❌ STO element not found!');
        alert('Error: STO element not found!');
    }
}

// Fungsi untuk auto-trigger jika ada nilai awal
function autoTriggerWitelStoSite() {
    const witelSelect = document.getElementById('witel');
    if (witelSelect && witelSelect.value) {
        console.log('🔄 Auto-triggering populateSTO for existing Witel value:', witelSelect.value);
        populateSTO(witelSelect.value);
    }
}

// Inisialisasi saat DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM Content Loaded - Initializing Witel → STO → Site Name dropdown');
    console.log('📊 STO Data available:', stoData);
    console.log('📊 Site Name Data available:', siteNameData);
    
    // Setup event listeners
    setupWitelStoSiteListeners();
    
    // Auto-trigger untuk nilai yang sudah ada
    setTimeout(autoTriggerWitelStoSite, 100);
    
    console.log('✅ Witel → STO → Site Name dropdown initialization complete');
});

// Export untuk penggunaan global
window.populateSTO = populateSTO;
window.populateSiteName = populateSiteName;
window.setupWitelStoSiteListeners = setupWitelStoSiteListeners;
window.stoData = stoData;
window.siteNameData = siteNameData;
