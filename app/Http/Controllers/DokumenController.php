<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Dokumen::with('user')->where('user_id', Auth::id());

        // Filter berdasarkan jenis proyek
        if ($request->filled('jenis_proyek')) {
            $query->where('jenis_proyek', $request->jenis_proyek);
        }

        // Filter berdasarkan status implementasi
        if ($request->filled('status_implementasi')) {
            $query->where('status_implementasi', $request->status_implementasi);
        }

        // Filter berdasarkan nomor kontak
        if ($request->filled('nomor_kontak')) {
            $query->where('nomor_kontak', 'like', '%' . $request->nomor_kontak . '%');
        }

        // Filter berdasarkan lokasi
        if ($request->filled('witel')) {
            $query->where('witel', $request->witel);
        }
        if ($request->filled('sto')) {
            $query->where('sto', $request->sto);
        }
        if ($request->filled('site_name')) {
            $query->where('site_name', $request->site_name);
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
            'jenis_proyek' => 'required|in:Instalasi Baru,Migrasi,Upgrade,Maintenance,Troubleshooting,Survey,Audit,Lainnya',
            'nomor_kontak' => 'required|string|max:255',
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
        // Pastikan user hanya bisa melihat dokumennya sendiri
        if ($dokumen->user_id !== Auth::id()) {
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
            'jenis_proyek' => 'required|in:Instalasi Baru,Migrasi,Upgrade,Maintenance,Troubleshooting,Survey,Audit,Lainnya',
            'nomor_kontak' => 'required|string|max:255',
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
            'jenis_proyek' => $dokumen->jenis_proyek,
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
        // Pastikan user hanya bisa download dokumennya sendiri
        if ($dokumen->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$dokumen->file_path || !Storage::disk('public')->exists($dokumen->file_path)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        return Storage::disk('public')->download($dokumen->file_path);
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