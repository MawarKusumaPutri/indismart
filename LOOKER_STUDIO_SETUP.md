# Looker Studio Dashboard Setup Guide

## Overview
Sistem Indismart telah diintegrasikan dengan Google Looker Studio (sebelumnya Google Data Studio) untuk memberikan dashboard analytics yang interaktif dan real-time untuk monitoring performa mitra dan proyek.

## Fitur Dashboard

### 📊 **Analytics yang Tersedia**
- **Overview Metrics**: Total mitra, proyek, completion rate
- **Mitra Analytics**: Top performing mitra, registration trends
- **Proyek Analytics**: Status distribution, jenis proyek, timeline
- **Performance Metrics**: KPI tracking, trends analysis
- **Real-time Data**: Auto-refresh setiap 5 menit

### 🔗 **API Endpoints**
Sistem menyediakan endpoint API berikut untuk Looker Studio:

1. **Complete Dashboard Data**
   ```
   GET /looker-studio/dashboard-data
   ```

2. **Export Data**
   ```
   GET /looker-studio/export-data
   ```

3. **Mitra Analytics**
   ```
   GET /api/mitra-analytics
   ```

4. **Proyek Analytics**
   ```
   GET /api/proyek-analytics
   ```

## Setup Instructions

### Step 1: Akses Looker Studio Dashboard
1. Login ke sistem sebagai Staff
2. Klik menu "Analytics Dashboard" di sidebar
3. Dashboard akan menampilkan quick stats dan embed area

### Step 2: Buat Data Source di Looker Studio
1. Buka [Google Looker Studio](https://lookerstudio.google.com)
2. Klik "Create" → "Data source"
3. Pilih "URL" connector
4. Masukkan salah satu endpoint API:
   ```
   https://your-domain.com/looker-studio/dashboard-data
   ```
5. Klik "Connect"

### Step 3: Buat Dashboard
1. Setelah data source terhubung, klik "Create Report"
2. Tambahkan visualisasi yang diinginkan:
   - **Time Series Chart**: Untuk trends over time
   - **Pie Chart**: Untuk status distribution
   - **Bar Chart**: Untuk top performing mitra
   - **Scorecard**: Untuk key metrics
   - **Table**: Untuk detailed data

### Step 4: Embed Dashboard
1. Di Looker Studio, klik "Share" → "Embed report"
2. Copy embed URL
3. Paste URL di field "Embed URL" di dashboard sistem
4. Klik "Embed"

## Data Structure

### Overview Data
```json
{
  "overview": {
    "total_mitra": 150,
    "total_dokumen": 450,
    "proyek_aktif": 120,
    "proyek_selesai": 330,
    "dokumen_pending_review": 25,
    "mitra_growth_monthly": 12,
    "proyek_growth_monthly": 45,
    "completion_rate": 73.33
  }
}
```

### Mitra Analytics
```json
{
  "mitra_analytics": {
    "top_performing_mitra": [
      {
        "id": 1,
        "name": "Mitra A",
        "email": "mitra@example.com",
        "total_proyek": 15,
        "proyek_selesai": 12,
        "success_rate": 80.0,
        "joined_date": "2024-01-15"
      }
    ],
    "registration_trend": [
      {
        "date": "2024-01-01",
        "count": 5
      }
    ],
    "mitra_by_status": [
      {
        "status": "Aktif",
        "count": 120
      },
      {
        "status": "Belum Aktif",
        "count": 30
      }
    ]
  }
}
```

### Proyek Analytics
```json
{
  "proyek_analytics": {
    "status_distribution": {
      "inisiasi": 25,
      "planning": 30,
      "executing": 45,
      "controlling": 20,
      "closing": 330
    },
    "jenis_proyek_distribution": [
      {
        "jenis_proyek": "Infrastruktur",
        "count": 150
      },
      {
        "jenis_proyek": "Aplikasi",
        "count": 200
      }
    ],
    "proyek_timeline": [
      {
        "date": "2024-01-01",
        "count": 10
      }
    ],
    "avg_completion_time_days": 45.5
  }
}
```

## Recommended Charts

### 1. **KPI Scorecard**
- Total Mitra
- Total Proyek
- Completion Rate
- Active Projects

### 2. **Time Series Chart**
- New Mitra Registration
- New Projects Created
- Completed Projects

### 3. **Pie Chart**
- Project Status Distribution
- Mitra Status Distribution
- Project Type Distribution

### 4. **Bar Chart**
- Top Performing Mitra
- Monthly Growth Trends
- Project Completion by Month

### 5. **Table**
- Detailed Mitra List
- Project Details
- Activity Timeline

## Customization Tips

### Color Scheme
Gunakan warna brand Indismart:
- Primary: `#e22626` (Red)
- Success: `#28a745` (Green)
- Warning: `#ffc107` (Yellow)
- Info: `#17a2b8` (Cyan)

### Filters
Tambahkan filter untuk:
- Date Range
- Mitra Selection
- Project Status
- Project Type

### Auto-refresh
Set dashboard untuk auto-refresh setiap 5-10 menit untuk data real-time.

## Troubleshooting

### Common Issues

1. **API Connection Failed**
   - Pastikan URL endpoint benar
   - Cek authentication (login sebagai staff)
   - Verifikasi CORS settings

2. **Data Not Loading**
   - Refresh halaman dashboard
   - Cek network tab di browser developer tools
   - Verifikasi data di database

3. **Embed Not Working**
   - Pastikan embed URL valid
   - Cek sharing settings di Looker Studio
   - Verifikasi domain permissions

### Support
Jika mengalami masalah, hubungi:
- Technical Support: support@indismart.com
- Documentation: [Internal Wiki](link-to-wiki)

## Security Notes

- Semua API endpoints memerlukan authentication
- Data hanya dapat diakses oleh user dengan role Staff
- API responses tidak mengandung sensitive information
- Rate limiting diterapkan untuk mencegah abuse

## Performance Optimization

- Data di-cache untuk performa optimal
- Pagination diterapkan untuk large datasets
- Compression enabled untuk API responses
- CDN digunakan untuk static assets

---

**Last Updated**: January 2025
**Version**: 1.0
**Author**: Indismart Development Team
