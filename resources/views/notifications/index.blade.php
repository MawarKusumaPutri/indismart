@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="page-title">Notifikasi</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Notifikasi</li>
            </ol>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-primary" id="markAllAsReadBtn">
                <i class="bi bi-check-all me-1"></i> Tandai Semua Dibaca
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Daftar Notifikasi</h5>
            </div>
            <div class="card-body">
                @if($notifications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item d-flex align-items-start {{ $notification->read_at ? '' : 'bg-light' }}" data-id="{{ $notification->id }}">
                                <div class="me-3">
                                    @php
                                        $iconClass = match($notification->type) {
                                            'success' => 'bi bi-check-circle text-success',
                                            'warning' => 'bi bi-exclamation-triangle text-warning',
                                            'error' => 'bi bi-x-circle text-danger',
                                            default => 'bi bi-info-circle text-info'
                                        };
                                    @endphp
                                    <i class="{{ $iconClass }} fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1 {{ $notification->read_at ? '' : 'fw-bold' }}">{{ $notification->title }}</h6>
                                            <p class="mb-1">{{ $notification->message }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            
                                            @if($notification->data)
                                                <div class="mt-2">
                                                    @if(isset($notification->data['jenis_proyek']))
                                                        <span class="badge bg-primary me-1">{{ $notification->data['jenis_proyek'] }}</span>
                                                    @endif
                                                    @if(isset($notification->data['lokasi']))
                                                        <span class="badge bg-info me-1">{{ $notification->data['lokasi'] }}</span>
                                                    @endif
                                                    @if(isset($notification->data['status']))
                                                        <span class="badge bg-secondary">{{ $notification->data['status'] }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            @if(!$notification->read_at)
                                                <button class="btn btn-sm btn-outline-success mark-as-read-btn" data-id="{{ $notification->id }}">
                                                    <i class="bi bi-check"></i> Tandai Dibaca
                                                </button>
                                            @else
                                                <span class="badge bg-success">Dibaca</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash display-1 text-muted"></i>
                        <h5 class="mt-3">Tidak ada notifikasi</h5>
                        <p class="text-muted">Anda belum memiliki notifikasi apapun.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark individual notification as read
    document.querySelectorAll('.mark-as-read-btn').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            
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
                    const notificationItem = this.closest('.list-group-item');
                    notificationItem.classList.remove('bg-light');
                    this.innerHTML = '<span class="badge bg-success">Dibaca</span>';
                    this.classList.remove('btn-outline-success', 'mark-as-read-btn');
                    this.classList.add('btn-success');
                }
            });
        });
    });

    // Mark all as read
    document.getElementById('markAllAsReadBtn').addEventListener('click', function() {
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
                // Reload page to update all notifications
                window.location.reload();
            }
        });
    });
});
</script>
@endpush 