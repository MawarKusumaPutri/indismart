@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notifikasi</h5>
                    @if($notifications->where('read_at', null)->count() > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary">
                                Tandai Semua Sudah Dibaca
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $notification->title }}</h6>
                                            <p class="mb-1">{{ $notification->message }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if(is_null($notification->read_at))
                                            <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    Tandai Sudah Dibaca
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center p-3">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-bell display-4 text-muted"></i>
                            <p class="mt-3 text-muted">Tidak ada notifikasi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection