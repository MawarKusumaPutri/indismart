<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Review;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of dokumen yang perlu direview
     */
    public function index(Request $request)
    {
        $query = Dokumen::with(['user', 'latestReview.reviewer'])
            ->whereHas('user', function($q) {
                $q->where('role', 'mitra');
            });

        // Filter berdasarkan status review
        if ($request->filled('status')) {
            switch($request->status) {
                case 'pending':
                    $query->whereDoesntHave('reviews')
                        ->orWhereHas('reviews', function($q) {
                            $q->where('status', 'pending');
                        });
                    break;
                case 'approved':
                    $query->whereHas('reviews', function($q) {
                        $q->where('status', 'approved');
                    });
                    break;
                case 'rejected':
                    $query->whereHas('reviews', function($q) {
                        $q->where('status', 'rejected');
                    });
                    break;
            }
        }

        // Filter berdasarkan jenis proyek
        if ($request->filled('jenis_proyek')) {
            $query->where('jenis_proyek', $request->jenis_proyek);
        }

        // Filter berdasarkan mitra
        if ($request->filled('mitra')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->mitra . '%');
            });
        }

        $dokumen = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('reviews.index', compact('dokumen'));
    }

    /**
     * Show the form for reviewing a dokumen
     */
    public function create(Dokumen $dokumen)
    {
        // Pastikan hanya karyawan yang bisa review
        if (!Auth::user()->isKaryawan()) {
            abort(403);
        }

        // Pastikan dokumen dari mitra
        if (!$dokumen->user->isMitra()) {
            abort(403);
        }

        return view('reviews.create', compact('dokumen'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request, Dokumen $dokumen)
    {
        // Pastikan hanya karyawan yang bisa review
        if (!Auth::user()->isKaryawan()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $review = Review::create([
            'dokumen_id' => $dokumen->id,
            'reviewer_id' => Auth::id(),
            'status' => $request->status,
            'komentar' => $request->komentar,
            'reviewed_at' => now()->setTimezone('Asia/Jakarta'),
        ]);

        // Kirim notifikasi ke mitra
        $this->notifyMitra($dokumen, $review);

        return redirect()->route('reviews.index')
            ->with('success', 'Review berhasil disimpan!');
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        // Pastikan hanya karyawan yang bisa lihat review
        if (!Auth::user()->isKaryawan()) {
            abort(403);
        }

        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review
     */
    public function edit(Review $review)
    {
        // Pastikan hanya karyawan yang bisa edit review
        if (!Auth::user()->isKaryawan()) {
            abort(403);
        }

        // Pastikan hanya reviewer yang bisa edit
        if ($review->reviewer_id !== Auth::id()) {
            abort(403);
        }

        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, Review $review)
    {
        // Pastikan hanya karyawan yang bisa update review
        if (!Auth::user()->isKaryawan()) {
            abort(403);
        }

        // Pastikan hanya reviewer yang bisa update
        if ($review->reviewer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'status' => $request->status,
            'komentar' => $request->komentar,
            'reviewed_at' => now()->setTimezone('Asia/Jakarta'),
        ]);

        // Kirim notifikasi ke mitra
        $this->notifyMitra($review->dokumen, $review);

        return redirect()->route('reviews.index')
            ->with('success', 'Review berhasil diperbarui!');
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        // Pastikan hanya karyawan yang bisa hapus review
        if (!Auth::user()->isKaryawan()) {
            abort(403);
        }

        // Pastikan hanya reviewer yang bisa hapus
        if ($review->reviewer_id !== Auth::id()) {
            abort(403);
        }

        $review->delete();

        return redirect()->route('reviews.index')
            ->with('success', 'Review berhasil dihapus!');
    }

    /**
     * Get dokumen yang perlu direview (API)
     */
    public function getPendingReviews()
    {
        $pendingDokumen = Dokumen::with(['user', 'latestReview.reviewer'])
            ->whereHas('user', function($q) {
                $q->where('role', 'mitra');
            })
            ->whereDoesntHave('reviews')
            ->orWhereHas('reviews', function($q) {
                $q->where('status', 'pending');
            })
            ->count();

        return response()->json(['count' => $pendingDokumen]);
    }

    /**
     * Notifikasi ke mitra
     */
    private function notifyMitra(Dokumen $dokumen, Review $review)
    {
        $statusLabel = [
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            'pending' => 'sedang ditinjau'
        ];

        $title = 'Review Dokumen: ' . $dokumen->nama_proyek;
        $message = "Dokumen {$dokumen->jenis_proyek} telah {$statusLabel[$review->status]} oleh {$review->reviewer->name}";
        
        $type = match($review->status) {
            'approved' => 'success',
            'rejected' => 'error',
            'pending' => 'info'
        };
        
        $data = [
            'dokumen_id' => $dokumen->id,
            'reviewer_name' => $review->reviewer->name,
            'status' => $review->status,
            'jenis_proyek' => $dokumen->jenis_proyek,
            'komentar' => $review->komentar,
            'rating' => $review->rating,
            'reviewed_at' => $review->reviewed_at->format('d M Y H:i')
        ];

        // Kirim notifikasi ke mitra
        NotificationService::notifyUser($dokumen->user_id, $title, $message, $type, $data);
    }
} 