@extends('layouts.app')

@section('title', 'Semua Notifikasi')

@section('content')
<div class="container-fluid">
    <!-- Statistik Notifikasi -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-bell display-6 text-primary"></i>
                    <h4 class="mt-2">{{ $stats['total'] }}</h4>
                    <p class="text-muted mb-0">Total Notifikasi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-bell-fill display-6 text-warning"></i>
                    <h4 class="mt-2">{{ $stats['unread'] }}</h4>
                    <p class="text-muted mb-0">Belum Dibaca</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-check-circle display-6 text-success"></i>
                    <h4 class="mt-2">{{ $stats['read'] }}</h4>
                    <p class="text-muted mb-0">Sudah Dibaca</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-calendar-today display-6 text-info"></i>
                    <h4 class="mt-2">{{ $stats['today'] }}</h4>
                    <p class="text-muted mb-0">Hari Ini</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">📋 Semua Notifikasi</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            @if($stats['unread'] > 0)
                                <button type="button" class="btn btn-sm btn-secondary" onclick="markAllAsRead()">
                                    <i class="bi bi-check-all"></i> Tandai Semua Dibaca
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Filter Form -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('notifications.index') }}" class="d-flex gap-3">
                                <div class="flex-fill">
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">Semua Status</option>
                                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                                    </select>
                                </div>
                                <div class="flex-fill">
                                    <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">Semua Jenis</option>
                                        @foreach($types as $type => $count)
                                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }} ({{ $count }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(request('status') || request('type'))
                                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i> Reset
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item {{ is_null($notification->read_at) ? 'notification-unread' : 'notification-read' }}">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <div class="notification-icon">
                                                @switch($notification->type)
                                                    @case('success')
                                                        <i class="bi bi-check-circle text-success fs-4"></i>
                                                        @break
                                                    @case('warning') 
                                                        <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
                                                        @break
                                                    @case('error')
                                                        <i class="bi bi-x-circle text-danger fs-4"></i>
                                                        @break
                                                    @default
                                                        <i class="bi bi-info-circle text-info fs-4"></i>
                                                @endswitch
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="notification-content">
                                                    <h6 class="mb-1 notification-title">{{ $notification->title }}</h6>
                                                    <p class="mb-2 notification-message">{{ $notification->message }}</p>
                                                    <div class="notification-meta">
                                                        <small class="text-muted me-3">
                                                            <i class="bi bi-clock"></i> {{ $notification->created_at->format('d M Y, H:i') }}
                                                        </small>
                                                        <small class="text-muted me-3">
                                                            <i class="bi bi-tag"></i> {{ ucfirst($notification->type) }}
                                                        </small>
                                                        @if($notification->read_at)
                                                            <small class="text-success">
                                                                <i class="bi bi-check-circle-fill"></i> Dibaca {{ $notification->read_at->diffForHumans() }}
                                                            </small>
                                                        @else
                                                            <small class="text-warning">
                                                                <i class="bi bi-circle-fill"></i> Belum dibaca
                                                            </small>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($notification->data && is_array($notification->data))
                                                        <div class="notification-data mt-2">
                                                            @if(isset($notification->data['action_url']))
                                                                <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-outline-primary me-2">
                                                                    <i class="bi bi-eye"></i> Lihat Detail
                                                                </a>
                                                            @endif
                                                            @if(isset($notification->data['dokumen_id']))
                                                                <span class="badge bg-secondary me-1">
                                                                    <i class="bi bi-file-text"></i> Dokumen #{{ $notification->data['dokumen_id'] }}
                                                                </span>
                                                            @endif
                                                            @if(isset($notification->data['mitra_name']))
                                                                <span class="badge bg-info me-1">
                                                                    <i class="bi bi-person"></i> {{ $notification->data['mitra_name'] }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="notification-actions">
                                                    @if(is_null($notification->read_at))
                                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="markAsRead({{ $notification->id }})">
                                                            <i class="bi bi-check"></i> Tandai Dibaca
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center p-3">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            @if(request('status') || request('type'))
                                <i class="bi bi-search display-4 text-muted"></i>
                                <p class="mt-3 text-muted">Tidak ada notifikasi sesuai filter</p>
                                <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-clockwise"></i> Lihat Semua Notifikasi
                                </a>
                            @else
                                <i class="bi bi-bell display-4 text-muted"></i>
                                <p class="mt-3 text-muted">Tidak ada notifikasi</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .notification-unread {
        background-color: #f8f9fa;
        border-left: 4px solid #ffc107;
    }
    
    .notification-read {
        background-color: #ffffff;
        border-left: 4px solid #e9ecef;
        opacity: 0.8;
    }
    
    .notification-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0,0,0,0.05);
        border-radius: 50%;
    }
    
    .notification-title {
        font-weight: 600;
        color: #212529;
    }
    
    .notification-message {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.4;
    }
    
    .notification-meta {
        border-top: 1px solid #e9ecef;
        padding-top: 10px;
        margin-top: 10px;
    }
    
    .notification-data {
        padding: 10px;
        background-color: rgba(0,0,0,0.02);
        border-radius: 5px;
        border: 1px solid #e9ecef;
    }
    
    .list-group-item {
        padding: 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .list-group-item:hover {
        background-color: rgba(0,0,0,0.02);
    }
    
    .notification-actions {
        min-width: 120px;
    }
    
    .card {
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
</style>
@endpush

@push('scripts')
<script>
    function markAsRead(notificationId) {
        fetch('{{ route("notifications.mark-as-read") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({notification_id: notificationId})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menandai notifikasi sebagai sudah dibaca');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
    
    function markAllAsRead() {
        if (confirm('Tandai semua notifikasi sebagai sudah dibaca?')) {
            fetch('{{ route("notifications.mark-all-as-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menandai semua notifikasi sebagai sudah dibaca');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }
    }
</script>
@endpush