<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{
    public function __construct()
    {
        // Middleware untuk membatasi akses create, store, edit, update, destroy hanya untuk mitra
        $this->middleware('role:mitra')->only(['create', 'store', 'edit', 'update', 'destroy', 'deleteFile']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Dokumen::with('user');
        
        // Jika user adalah mitra, hanya tampilkan dokumen miliknya
        if ($user->isMitra()) {
            $query->where('user_id', $user->id);
        }
        // Jika user adalah staff, tampilkan semua dokumen

        // Filter berdasarkan jenis proyek
        if ($request->filled('jenis_proyek')) {
            $query->where('jenis_proyek', $request->jenis_proyek);
        }

        // Filter berdasarkan status implementasi
        if ($request->filled('status_implementasi')) {
            $query->where('status_implementasi', $request->status_implementasi);
        }

        // Filter berdasarkan status review
        if ($request->filled('status_review')) {
            switch($request->status_review) {
                case 'approved':
                    $query->whereHas('reviews', function($q) {
                        $q->where('status', 'approved')
                          ->whereIn('id', function($sub) {
                              $sub->selectRaw('MAX(id)')
                                  ->from('reviews')
                                  ->groupBy('dokumen_id');
                          });
                    });
                    break;
                case 'rejected':
                    $query->whereHas('reviews', function($q) {
                        $q->where('status', 'rejected')
                          ->whereIn('id', function($sub) {
                              $sub->selectRaw('MAX(id)')
                                  ->from('reviews')
                                  ->groupBy('dokumen_id');
                          });
                    });
                    break;
                case 'pending':
                    $query->whereHas('reviews', function($q) {
                        $q->where('status', 'pending')
                          ->whereIn('id', function($sub) {
                              $sub->selectRaw('MAX(id)')
                                  ->from('reviews')
                                  ->groupBy('dokumen_id');
                          });
                    });
                    break;
                case 'none':
                    $query->doesntHave('reviews');
                    break;
            }
        }

        // Search keseluruhan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_dokumen', 'like', '%' . $search . '%')
                  ->orWhere('jenis_proyek', 'like', '%' . $search . '%')
                  ->orWhere('nomor_kontrak', 'like', '%' . $search . '%')
                  ->orWhere('witel', 'like', '%' . $search . '%')
                  ->orWhere('sto', 'like', '%' . $search . '%')
                  ->orWhere('site_name', 'like', '%' . $search . '%')
                  ->orWhere('status_implementasi', 'like', '%' . $search . '%')
                  ->orWhere('keterangan', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        } else {
            // Filter individual jika tidak ada search
            // Filter berdasarkan nomor kontrak
            if ($request->filled('nomor_kontrak')) {
                $query->where('nomor_kontrak', 'like', '%' . $request->nomor_kontrak . '%');
            }

            // Filter berdasarkan lokasi
            if ($request->filled('witel')) {
                $query->where('witel', 'like', '%' . $request->witel . '%');
            }
            if ($request->filled('sto')) {
                $query->where('sto', 'like', '%' . $request->sto . '%');
            }
            if ($request->filled('site_name')) {
                $query->where('site_name', 'like', '%' . $request->site_name . '%');
            }
        }

        $dokumen = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('dokumen.index', compact('dokumen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dokumen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'jenis_proyek' => 'required|in:Instalasi Baru,Migrasi,Upgrade,Maintenance,Troubleshooting,Survey,Audit,Lainnya',
            'nomor_kontrak' => 'required|string|max:255',
            'witel' => 'required|string|max:255',
            'sto' => 'required|string|max:255',
            'site_name' => 'required|string|max:255',
            'status_implementasi' => 'required|in:inisiasi,planning,executing,controlling,closing',
            'tanggal_dokumen' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        
        // Auto-fill nomor kontrak from user's assigned contract number
        if (Auth::user()->nomor_kontrak) {
            $data['nomor_kontrak'] = Auth::user()->nomor_kontrak;
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('dokumen', $fileName, 'public');
            $data['file_path'] = $filePath;
        }

        $dokumen = Dokumen::create($data);

        // Kirim notifikasi ke staff
        NotificationService::notifyDokumenUploaded($dokumen);

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dokumen $dokumen)
    {
        $user = Auth::user();
        
        // Jika user adalah mitra, pastikan hanya bisa melihat dokumennya sendiri
        if ($user->isMitra() && $dokumen->user_id !== $user->id) {
            abort(403);
        }

        return view('dokumen.show', compact('dokumen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dokumen $dokumen)
    {
        // Pastikan user hanya bisa mengedit dokumennya sendiri
        if ($dokumen->user_id !== Auth::id()) {
            abort(403);
        }

        return view('dokumen.edit', compact('dokumen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dokumen $dokumen)
    {
        // Pastikan user hanya bisa mengupdate dokumennya sendiri
        if ($dokumen->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'jenis_proyek' => 'required|in:Instalasi Baru,Migrasi,Upgrade,Maintenance,Troubleshooting,Survey,Audit,Lainnya',
            'nomor_kontrak' => 'required|string|max:255',
            'witel' => 'required|string|max:255',
            'sto' => 'required|string|max:255',
            'site_name' => 'required|string|max:255',
            'status_implementasi' => 'required|in:inisiasi,planning,executing,controlling,closing',
            'tanggal_dokumen' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->except(['file']);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('dokumen', $fileName, 'public');
            $data['file_path'] = $filePath;
        }

        $dokumen->update($data);

        // Kirim notifikasi ke staff
        NotificationService::notifyDokumenUpdated($dokumen);

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dokumen $dokumen)
    {
        // Pastikan user hanya bisa menghapus dokumennya sendiri
        if ($dokumen->user_id !== Auth::id()) {
            abort(403);
        }

        // Simpan data dokumen sebelum dihapus untuk notifikasi
        $dokumenData = [
            'mitra_name' => $dokumen->user->name,
            'nama_dokumen' => $dokumen->nama_dokumen,
            'nama_proyek' => $dokumen->jenis_proyek . ' ' . $dokumen->sto . '-' . $dokumen->site_name . ' (' . $dokumen->nomor_kontrak . ')',
            'jenis_proyek' => $dokumen->jenis_proyek,
            'lokasi' => "{$dokumen->witel} - {$dokumen->sto} - {$dokumen->site_name}",
        ];

        // Hapus file jika ada
        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        // Kirim notifikasi ke staff
        NotificationService::notifyDokumenDeleted($dokumenData);

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil dihapus!');
    }

    /**
     * Download file dokumen
     */
    public function download(Dokumen $dokumen)
    {
        $user = Auth::user();
        
        // Mitra hanya bisa download dokumennya sendiri
        // Staff bisa download semua dokumen
        if ($user->isMitra() && $dokumen->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk download dokumen ini.');
        }

        if (!$dokumen->file_path || !Storage::disk('public')->exists($dokumen->file_path)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        // Get original filename
        $originalName = basename($dokumen->file_path);
        if (strpos($originalName, '_') !== false) {
            $originalName = substr($originalName, strpos($originalName, '_') + 1);
        }

        return Storage::disk('public')->download($dokumen->file_path, $originalName);
    }

    /**
     * Delete file dokumen
     */
    public function deleteFile(Dokumen $dokumen)
    {
        // Pastikan user hanya bisa menghapus file dokumennya sendiri
        if ($dokumen->user_id !== Auth::id()) {
            abort(403);
        }

        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
            $dokumen->update(['file_path' => null]);
            return back()->with('success', 'File berhasil dihapus!');
        }

        return back()->with('error', 'File tidak ditemukan!');
    }
} 