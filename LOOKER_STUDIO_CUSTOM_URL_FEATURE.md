# Looker Studio Custom URL Feature

## Overview
Fitur ini memungkinkan pengguna untuk memasukkan URL Looker Studio eksternal yang sudah dibuat sebelumnya, sehingga tidak perlu membuat dashboard baru secara otomatis.

## Fitur Utama

### 1. Input URL Eksternal
- Pengguna dapat memasukkan URL Looker Studio yang sudah ada
- Validasi format URL (harus dari `https://lookerstudio.google.com`)
- Support untuk Enter key untuk submit

### 2. Penyimpanan URL
- URL disimpan dalam session Laravel
- URL akan tetap tersedia selama session aktif
- Mendukung multiple user dengan session terpisah

### 3. Tampilan Dashboard
- Otomatis menampilkan URL yang tersimpan saat halaman dimuat
- Toggle antara URL otomatis dan URL eksternal
- Indikator visual untuk jenis URL yang sedang aktif

## Implementasi Teknis

### Backend (Controller)

#### LookerStudioController.php
```php
// Method untuk menyimpan custom URL
public function setCustomUrl(Request $request)
{
    // Validasi input
    // Penyimpanan ke session
    // Response JSON
}

// Method untuk mendapatkan URL saat ini
public function getCurrentUrl()
{
    // Cek session untuk custom URL
    // Generate URL otomatis jika tidak ada custom URL
    // Response dengan tipe URL (custom/generated)
}
```

### Frontend (JavaScript)

#### Fungsi Utama
```javascript
// Set custom URL
function setCustomUrl() {
    // Validasi input
    // API call ke backend
    // Update UI
}

// Check existing URL
function checkExistingUrl() {
    // Cek URL yang tersimpan
    // Tampilkan jika ada
}

// Show dashboard URL
function showDashboardUrl() {
    // Ambil URL saat ini
    // Tampilkan di UI
}
```

### Routes
```php
// Web routes
Route::post('/looker-studio/set-custom-url', [LookerStudioController::class, 'setCustomUrl']);
Route::get('/looker-studio/get-current-url', [LookerStudioController::class, 'getCurrentUrl']);
```

## UI Components

### Input Section
```html
<div class="mt-4">
    <h6>Atau Masukkan URL Looker Studio Eksternal</h6>
    <div class="input-group mb-3">
        <input type="url" class="form-control" id="customUrlInput" 
               placeholder="https://lookerstudio.google.com/reporting/..." 
               pattern="https://lookerstudio\.google\.com.*">
        <button class="btn btn-outline-primary" type="button" onclick="setCustomUrl()">
            <i class="bi bi-check-circle me-1"></i>
            Set URL
        </button>
    </div>
</div>
```

### URL Display Section
```html
<div id="dashboardUrlSection" style="display: none;">
    <div class="input-group">
        <input type="text" class="form-control" id="dashboardUrl" readonly>
        <button class="btn btn-outline-secondary" type="button" onclick="copyDashboardUrl()">
            <i class="bi bi-clipboard me-1"></i>
            Copy
        </button>
        <a href="#" id="openDashboardBtn" class="btn btn-primary" target="_blank">
            <i class="bi bi-box-arrow-up-right me-1"></i>
            Buka Dashboard
        </a>
    </div>
</div>
```

## Validasi

### Client-side Validation
- URL tidak boleh kosong
- Format URL harus valid
- URL harus dari domain Looker Studio

### Server-side Validation
```php
$request->validate([
    'custom_url' => 'required|url|starts_with:https://lookerstudio.google.com'
], [
    'custom_url.required' => 'URL Looker Studio harus diisi.',
    'custom_url.url' => 'Format URL tidak valid.',
    'custom_url.starts_with' => 'URL harus dari Looker Studio (https://lookerstudio.google.com).'
]);
```

## Error Handling

### Frontend Errors
- CSRF token tidak ditemukan
- Network errors
- Validation errors
- Server errors

### Backend Errors
- Validation exceptions
- Session errors
- Database errors
- Authentication errors

## Security Features

### Authentication
- Hanya user dengan role 'staff' yang dapat mengakses
- CSRF protection untuk semua requests
- Session-based storage

### Input Sanitization
- URL validation
- XSS prevention
- SQL injection prevention

## User Experience

### Loading States
- Loading indicator saat setting URL
- Disabled button selama proses
- Visual feedback untuk semua actions

### Success/Error Messages
- Toast notifications
- Clear error messages
- Success confirmations

### Accessibility
- Keyboard navigation (Enter key)
- Screen reader friendly
- Proper ARIA labels

## Testing

### Manual Testing
1. Masukkan URL valid
2. Masukkan URL invalid
3. Test Enter key functionality
4. Test copy URL functionality
5. Test session persistence

### Automated Testing
```php
// Test cases untuk controller
public function testSetCustomUrl()
public function testGetCurrentUrl()
public function testValidation()
public function testAuthentication()
```

## Maintenance

### Logging
- Semua actions di-log
- Error tracking
- User activity monitoring

### Monitoring
- Session usage
- Error rates
- Performance metrics

## Troubleshooting

### Common Issues
1. **URL tidak tersimpan**: Cek session configuration
2. **Validation error**: Pastikan format URL benar
3. **Permission denied**: Cek user role
4. **CSRF error**: Refresh halaman

### Debug Steps
1. Check browser console
2. Check Laravel logs
3. Verify session data
4. Test API endpoints

## Future Enhancements

### Planned Features
- Database storage untuk URL (bukan session)
- URL sharing antar user
- URL templates
- Analytics untuk URL usage

### Performance Optimizations
- Caching untuk URL data
- Lazy loading
- Optimized API responses
