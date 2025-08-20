# 📊 API Documentation - Looker Studio Integration

## Overview
API ini menyediakan endpoint untuk integrasi dengan Google Looker Studio (sebelumnya Google Data Studio) untuk dashboard analytics yang interaktif.

## Base URL
```
https://your-domain.com/api/looker-studio
```

## Authentication
Semua endpoint memerlukan authentication. Gunakan session authentication atau token-based authentication.

## Endpoints

### 1. Dashboard Overview
**GET** `/api/looker-studio/dashboard-overview`

Mengambil data lengkap untuk dashboard overview.

**Response:**
```json
{
  "success": true,
  "data": {
    "overview": {
      "total_mitra": 150,
      "total_dokumen": 450,
      "proyek_aktif": 120,
      "proyek_selesai": 330,
      "dokumen_pending_review": 25,
      "mitra_growth_monthly": 12,
      "proyek_growth_monthly": 45,
      "completion_rate": 73.33
    },
    "mitra_analytics": {
      "top_performing_mitra": [...],
      "total_mitra": 150,
      "active_mitra": 120
    },
    "proyek_analytics": {
      "status_distribution": {...},
      "jenis_proyek_distribution": [...],
      "total_proyek": 450,
      "completed_proyek": 330
    },
    "trends": {
      "monthly_trends": [...]
    },
    "last_updated": "2025-01-20 10:30:00"
  },
  "message": "Dashboard overview data retrieved successfully"
}
```

### 2. Mitra Analytics
**GET** `/api/looker-studio/mitra-analytics`

Mengambil data analytics khusus mitra.

**Response:**
```json
{
  "success": true,
  "data": {
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
    "total_mitra": 150,
    "active_mitra": 120
  },
  "message": "Mitra analytics data retrieved successfully"
}
```

### 3. Proyek Analytics
**GET** `/api/looker-studio/proyek-analytics`

Mengambil data analytics khusus proyek.

**Response:**
```json
{
  "success": true,
  "data": {
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
    "total_proyek": 450,
    "completed_proyek": 330
  },
  "message": "Proyek analytics data retrieved successfully"
}
```

### 4. Performance Metrics
**GET** `/api/looker-studio/performance-metrics`

Mengambil metrics performa sistem.

**Response:**
```json
{
  "success": true,
  "data": {
    "total_proyek": 450,
    "completed_proyek": 330,
    "active_proyek": 120,
    "completion_rate": 73.33
  },
  "message": "Performance metrics retrieved successfully"
}
```

### 5. Monthly Trends
**GET** `/api/looker-studio/trends-monthly`

Mengambil data trend bulanan untuk 12 bulan terakhir.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "month": "2024-02",
      "month_name": "Feb 2024",
      "new_mitra": 5,
      "new_proyek": 15,
      "completed_proyek": 12
    }
  ],
  "message": "Monthly trends data retrieved successfully"
}
```

### 6. Export Complete Data
**GET** `/api/looker-studio/export-complete`

Mengambil data lengkap untuk export dan analysis.

**Response:**
```json
{
  "success": true,
  "data": {
    "mitra_data": [
      {
        "mitra_id": 1,
        "nama_mitra": "Mitra A",
        "email_mitra": "mitra@example.com",
        "tanggal_bergabung": "2024-01-15",
        "total_proyek": 15,
        "proyek_selesai": 12,
        "proyek_aktif": 3,
        "success_rate": 80.0,
        "status_mitra": "Aktif"
      }
    ],
    "proyek_data": [
      {
        "proyek_id": 1,
        "nama_proyek": "Proyek A",
        "jenis_proyek": "Infrastruktur",
        "status_implementasi": "closing",
        "mitra_id": 1,
        "nama_mitra": "Mitra A",
        "tanggal_mulai": "2024-01-15",
        "tanggal_update": "2024-02-15",
        "durasi_hari": 31,
        "is_completed": 1
      }
    ],
    "export_date": "2025-01-20 10:30:00"
  },
  "message": "Complete export data retrieved successfully"
}
```

## Error Response Format

Semua endpoint mengembalikan error dalam format yang konsisten:

```json
{
  "success": false,
  "message": "Error description"
}
```

**HTTP Status Codes:**
- `200` - Success
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `500` - Internal Server Error

## Data Fields Description

### Overview Fields
- `total_mitra` - Total jumlah mitra terdaftar
- `total_dokumen` - Total jumlah dokumen/proyek
- `proyek_aktif` - Jumlah proyek yang sedang aktif
- `proyek_selesai` - Jumlah proyek yang telah selesai
- `dokumen_pending_review` - Jumlah dokumen pending review
- `mitra_growth_monthly` - Pertumbuhan mitra bulanan
- `proyek_growth_monthly` - Pertumbuhan proyek bulanan
- `completion_rate` - Persentase penyelesaian proyek

### Mitra Analytics Fields
- `top_performing_mitra` - Array mitra terbaik berdasarkan jumlah proyek
- `total_mitra` - Total jumlah mitra
- `active_mitra` - Jumlah mitra yang aktif (memiliki nomor kontrak)

### Proyek Analytics Fields
- `status_distribution` - Distribusi status proyek
- `jenis_proyek_distribution` - Distribusi jenis proyek
- `total_proyek` - Total jumlah proyek
- `completed_proyek` - Jumlah proyek yang selesai

## Usage Examples

### cURL Examples

**Dashboard Overview:**
```bash
curl -X GET "https://your-domain.com/api/looker-studio/dashboard-overview" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Mitra Analytics:**
```bash
curl -X GET "https://your-domain.com/api/looker-studio/mitra-analytics" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### JavaScript Examples

**Fetch Dashboard Data:**
```javascript
fetch('/api/looker-studio/dashboard-overview')
  .then(response => response.json())
  .then(response => {
    if (response.success) {
      console.log('Dashboard data:', response.data);
    } else {
      console.error('Error:', response.message);
    }
  })
  .catch(error => {
    console.error('Network error:', error);
  });
```

**Export Data:**
```javascript
fetch('/api/looker-studio/export-complete')
  .then(response => response.json())
  .then(response => {
    if (response.success) {
      // Process export data
      const mitraData = response.data.mitra_data;
      const proyekData = response.data.proyek_data;
    }
  });
```

## Looker Studio Integration

### Data Source Setup
1. Buka Google Looker Studio
2. Klik "Create" → "Data source"
3. Pilih "URL" connector
4. Masukkan salah satu endpoint API
5. Klik "Connect"

### Recommended Charts
- **Time Series Chart** - Gunakan `/trends-monthly` untuk trends over time
- **Pie Chart** - Gunakan `status_distribution` atau `jenis_proyek_distribution`
- **Bar Chart** - Gunakan `top_performing_mitra` untuk ranking
- **Scorecard** - Gunakan overview metrics untuk KPI

### Data Refresh
- Set auto-refresh setiap 5-10 menit untuk data real-time
- Gunakan `last_updated` field untuk tracking data freshness

## Rate Limiting
- **Standard**: 100 requests per minute per user
- **Export endpoints**: 10 requests per minute per user
- **Bulk data**: 5 requests per minute per user

## Security Notes
- Semua endpoint memerlukan authentication
- Data hanya dapat diakses oleh user dengan role Staff
- API responses tidak mengandung sensitive information
- CORS enabled untuk Looker Studio domains

## Support
Untuk bantuan teknis, hubungi:
- **Email**: support@indismart.com
- **Documentation**: [Internal Wiki](link-to-wiki)
- **Issue Tracker**: [GitHub Issues](link-to-issues)

---

**Last Updated**: January 2025  
**Version**: 1.0  
**Status**: ✅ Production Ready
