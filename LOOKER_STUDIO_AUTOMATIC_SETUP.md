# Looker Studio Automatic Setup - Indismart

## 📊 Overview

Sistem Looker Studio otomatis untuk Indismart memungkinkan staff untuk membuat dashboard analytics secara otomatis dengan data real-time dari sistem. Fitur ini terintegrasi penuh dengan database Indismart dan menyediakan visualisasi data yang komprehensif.

## 🚀 Fitur Utama

### ✅ **Automatic Dashboard Generation**
- **One-Click Generation**: Buat dashboard Looker Studio dengan satu klik
- **Real-time Data**: Data langsung dari database Indismart
- **Pre-configured Charts**: Chart dan visualisasi yang sudah dikonfigurasi
- **Customizable**: Bisa dikustomisasi sesuai kebutuhan

### ✅ **Data Integration**
- **Complete Data Sources**: Dokumen, Mitra, Foto, Review
- **Real-time Updates**: Data terupdate secara real-time
- **Filtering Options**: Filter berdasarkan status, tanggal, lokasi
- **Export Capabilities**: Export data dalam berbagai format

### ✅ **Analytics Dashboard**
- **Summary Metrics**: Total mitra, dokumen, foto, review
- **Trend Analysis**: Analisis tren waktu
- **Distribution Charts**: Distribusi status dan jenis proyek
- **Activity Timeline**: Timeline aktivitas terbaru

## 🏗️ Architecture

### **Backend Components**
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── LookerStudioController.php          # Main controller
│   │   └── Api/
│   │       └── LookerStudioApiController.php   # API controller
│   └── Middleware/
│       └── RoleMiddleware.php                  # Role-based access
├── Models/
│   ├── User.php                               # Mitra data
│   ├── Dokumen.php                            # Document data
│   ├── Foto.php                               # Photo data
│   └── Review.php                             # Review data
└── Services/
    └── NotificationService.php                # Notifications
```

### **Frontend Components**
```
resources/views/
├── layouts/
│   └── app.blade.php                          # Main layout with sidebar
└── looker-studio/
    └── index.blade.php                        # Dashboard view
```

### **Routes**
```
routes/
├── web.php                                    # Web routes
└── api.php                                    # API routes
```

## 📋 Data Sources

### **1. Dokumen Data**
```php
// Fields available for Looker Studio
[
    'id' => 'Document ID',
    'nama_dokumen' => 'Document Name',
    'jenis_proyek' => 'Project Type',
    'status_implementasi' => 'Implementation Status',
    'witel' => 'Witel',
    'sto' => 'STO',
    'site_name' => 'Site Name',
    'tanggal_dokumen' => 'Document Date',
    'mitra' => 'Partner Name',
    'jumlah_foto' => 'Photo Count',
    'jumlah_review' => 'Review Count',
    'created_at' => 'Created Date',
    'updated_at' => 'Updated Date'
]
```

### **2. Mitra Data**
```php
// Fields available for Looker Studio
[
    'id' => 'Partner ID',
    'name' => 'Partner Name',
    'email' => 'Email',
    'nomor_kontrak' => 'Contract Number',
    'jumlah_dokumen' => 'Document Count',
    'jumlah_foto' => 'Photo Count',
    'registered_at' => 'Registration Date',
    'last_activity' => 'Last Activity',
    'status' => 'Status (Active/Inactive)'
]
```

### **3. Foto Data**
```php
// Fields available for Looker Studio
[
    'id' => 'Photo ID',
    'dokumen_id' => 'Document ID',
    'nama_dokumen' => 'Document Name',
    'mitra' => 'Partner Name',
    'caption' => 'Photo Caption',
    'file_size' => 'File Size',
    'uploaded_at' => 'Upload Date',
    'order' => 'Photo Order'
]
```

### **4. Review Data**
```php
// Fields available for Looker Studio
[
    'id' => 'Review ID',
    'dokumen_id' => 'Document ID',
    'nama_dokumen' => 'Document Name',
    'mitra' => 'Partner Name',
    'rating' => 'Rating',
    'komentar' => 'Comment',
    'status' => 'Review Status',
    'reviewed_at' => 'Review Date'
]
```

## 🔧 Installation & Setup

### **1. Prerequisites**
- Laravel 10+ application
- MySQL/PostgreSQL database
- Google Cloud Platform account (for BigQuery integration)
- Looker Studio access

### **2. Database Setup**
```bash
# Run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed
```

### **3. Configuration**
```php
// config/app.php
'google_project_id' => env('GOOGLE_PROJECT_ID', 'indismart-project'),
'looker_studio_enabled' => env('LOOKER_STUDIO_ENABLED', true),
```

### **4. Environment Variables**
```env
# .env
GOOGLE_PROJECT_ID=your-google-project-id
LOOKER_STUDIO_ENABLED=true
GOOGLE_APPLICATION_CREDENTIALS=path/to/service-account.json
```

## 🎯 Usage Guide

### **1. Accessing Looker Studio Dashboard**
1. Login sebagai staff
2. Klik menu "Looker Studio" di sidebar
3. Dashboard akan menampilkan overview data

### **2. Generating Looker Studio Dashboard**
1. Klik tombol "Generate Looker Studio Dashboard"
2. Sistem akan memproses data dan membuat dashboard
3. URL dashboard akan ditampilkan
4. Klik "Buka Dashboard" untuk membuka di Looker Studio

### **3. Exporting Data**
1. Pilih format export (JSON/CSV)
2. Klik tombol export yang sesuai
3. File akan didownload otomatis

### **4. Real-time Data Updates**
- Data diupdate setiap 30 detik
- Refresh manual tersedia
- Notifikasi untuk perubahan data

## 📊 Available Charts & Visualizations

### **1. Summary Cards**
- Total Mitra
- Total Dokumen
- Proyek Aktif
- Total Foto

### **2. Distribution Charts**
- **Status Distribution**: Pie chart distribusi status implementasi
- **Project Type Distribution**: Bar chart jenis proyek
- **Witel Distribution**: Top 10 Witel berdasarkan dokumen

### **3. Activity Tables**
- **Mitra Activity**: Tabel aktivitas mitra terbaru
- **Recent Activities**: Timeline aktivitas terbaru

### **4. Trend Analysis**
- **Weekly Trends**: Tren mingguan
- **Monthly Trends**: Tren bulanan
- **Quarterly Trends**: Tren kuartalan

## 🔌 API Endpoints

### **Analytics Data**
```
GET /api/looker-studio/analytics?type={data_type}
```

**Parameters:**
- `type`: summary, dokumen, mitra, foto, review, trends, charts
- `status`: Filter by status
- `witel`: Filter by Witel
- `date_from`: Start date
- `date_to`: End date

### **Real-time Data**
```
GET /api/looker-studio/realtime
```

### **Export Data**
```
GET /api/looker-studio/export?format={format}&type={type}
```

**Parameters:**
- `format`: json, csv
- `type`: dokumen, mitra, foto, review, all

## 🎨 Customization

### **1. Adding New Data Sources**
```php
// In LookerStudioController.php
private function formatNewData()
{
    return NewModel::with(['relations'])
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                // Add more fields
            ];
        });
}
```

### **2. Custom Charts**
```javascript
// In index.blade.php
function createCustomChart() {
    const ctx = document.getElementById('customChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Label 1', 'Label 2'],
            datasets: [{
                label: 'Custom Data',
                data: [1, 2],
                borderColor: '#4e73df'
            }]
        }
    });
}
```

### **3. Custom Filters**
```php
// In LookerStudioApiController.php
private function getCustomFilteredData(Request $request)
{
    $query = Model::query();
    
    if ($request->has('custom_filter')) {
        $query->where('field', $request->custom_filter);
    }
    
    return $query->get();
}
```

## 🔒 Security & Access Control

### **1. Role-based Access**
- Hanya staff yang bisa mengakses Looker Studio
- Middleware `role:staff` diterapkan
- API endpoints dilindungi

### **2. Data Privacy**
- Data mitra hanya bisa diakses staff
- Sensitive data tidak diekspos
- Audit trail untuk akses data

### **3. API Security**
- CSRF protection untuk web routes
- Rate limiting untuk API
- Input validation dan sanitization

## 📈 Performance Optimization

### **1. Database Optimization**
```sql
-- Indexes for better performance
CREATE INDEX idx_dokumen_status ON dokumen(status_implementasi);
CREATE INDEX idx_dokumen_created ON dokumen(created_at);
CREATE INDEX idx_foto_dokumen ON fotos(dokumen_id);
CREATE INDEX idx_review_dokumen ON reviews(dokumen_id);
```

### **2. Caching Strategy**
```php
// Cache frequently accessed data
Cache::remember('looker_studio_summary', 300, function () {
    return $this->getSummaryData();
});
```

### **3. Query Optimization**
```php
// Use eager loading to avoid N+1 queries
$dokumen = Dokumen::with(['user', 'fotos', 'reviews'])->get();
```

## 🐛 Troubleshooting

### **Common Issues**

#### **1. Dashboard Generation Fails**
```bash
# Check logs
tail -f storage/logs/laravel.log

# Verify Google Cloud credentials
php artisan tinker
config('app.google_project_id')
```

#### **2. Data Not Loading**
```bash
# Check database connection
php artisan migrate:status

# Verify data exists
php artisan tinker
App\Models\Dokumen::count()
```

#### **3. API Errors**
```bash
# Check API routes
php artisan route:list --path=api/looker-studio

# Test API endpoint
curl -X GET "http://localhost/api/looker-studio/analytics?type=summary"
```

### **Debug Mode**
```php
// Enable debug mode in .env
APP_DEBUG=true
LOG_LEVEL=debug
```

## 🔄 Maintenance

### **1. Regular Tasks**
- Monitor database performance
- Update Google Cloud credentials
- Backup dashboard configurations
- Review access logs

### **2. Data Cleanup**
```php
// Clean old data
php artisan schedule:run

// Archive old records
php artisan make:command ArchiveOldData
```

### **3. Performance Monitoring**
```php
// Monitor query performance
DB::enableQueryLog();
// ... your code ...
dd(DB::getQueryLog());
```

## 📚 Additional Resources

### **Documentation**
- [Laravel Documentation](https://laravel.com/docs)
- [Looker Studio Documentation](https://support.google.com/looker-studio/)
- [Google Cloud BigQuery](https://cloud.google.com/bigquery/docs)

### **Support**
- Technical issues: Create issue in repository
- Feature requests: Submit enhancement proposal
- Documentation: Update this file

## 🎉 Conclusion

Looker Studio Automatic Setup untuk Indismart menyediakan solusi analytics yang komprehensif dan mudah digunakan. Dengan integrasi real-time dan fitur otomatis, staff dapat dengan mudah membuat dan mengelola dashboard analytics untuk monitoring performa sistem.

---

**Status**: ✅ **READY FOR PRODUCTION**

Sistem Looker Studio otomatis telah siap digunakan dan terintegrasi penuh dengan aplikasi Indismart.
