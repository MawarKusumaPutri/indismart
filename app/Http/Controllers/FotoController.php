<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FotoController extends Controller
{
    public function __construct()
    {
        // Middleware untuk membatasi akses hanya untuk mitra
        $this->middleware('role:mitra')->only(['store', 'destroy', 'updateOrder']);
    }

    /**
     * Upload foto untuk dokumen
     */
    public function store(Request $request, Dokumen $dokumen)
    {
        // Pastikan user hanya bisa upload foto untuk dokumennya sendiri
        if ($dokumen->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk upload foto ke dokumen ini.');
        }

        $request->validate([
            'fotos.*' => 'required|image|mimes:jpg,jpeg,png|max:5120', // 5MB per foto
            'fotos' => 'required|array|min:3|max:10', // Minimal 3 foto, maksimal 10 foto
            'captions.*' => 'nullable|string|max:255',
        ], [
            'fotos.min' => 'Minimal harus upload 3 foto.',
            'fotos.max' => 'Maksimal hanya bisa upload 10 foto.',
            'fotos.*.image' => 'File harus berupa gambar.',
            'fotos.*.mimes' => 'Format gambar yang didukung: JPG, JPEG, PNG.',
            'fotos.*.max' => 'Ukuran foto maksimal 5MB.',
        ]);

        $uploadedFotos = [];
        $order = $dokumen->fotos()->count() + 1;

        foreach ($request->file('fotos') as $index => $foto) {
            $fileName = time() . '_' . $index . '_' . $foto->getClientOriginalName();
            $filePath = $foto->storeAs('fotos', $fileName, 'public');

            $caption = $request->input("captions.{$index}") ?? null;

            $fotoModel = Foto::create([
                'dokumen_id' => $dokumen->id,
                'file_path' => $filePath,
                'original_name' => $foto->getClientOriginalName(),
                'caption' => $caption,
                'order' => $order++,
            ]);

            $uploadedFotos[] = $fotoModel;
        }

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diupload!',
            'fotos' => $uploadedFotos,
            'total_fotos' => $dokumen->fotos()->count(),
        ]);
    }

    /**
     * Hapus foto
     */
    public function destroy(Foto $foto)
    {
        // Pastikan user hanya bisa menghapus foto dari dokumennya sendiri
        if ($foto->dokumen->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus foto ini.');
        }

        // Hapus file dari storage
        if ($foto->file_path && Storage::disk('public')->exists($foto->file_path)) {
            Storage::disk('public')->delete($foto->file_path);
        }

        $dokumenId = $foto->dokumen_id;
        $foto->delete();

        // Reorder foto yang tersisa
        $this->reorderFotos($dokumenId);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dihapus!',
            'total_fotos' => Dokumen::find($dokumenId)->fotos()->count(),
        ]);
    }

    /**
     * Update urutan foto
     */
    public function updateOrder(Request $request, Dokumen $dokumen)
    {
        // Pastikan user hanya bisa update urutan foto dari dokumennya sendiri
        if ($dokumen->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah urutan foto ini.');
        }

        $request->validate([
            'foto_ids' => 'required|array',
            'foto_ids.*' => 'exists:fotos,id',
        ]);

        foreach ($request->foto_ids as $order => $fotoId) {
            Foto::where('id', $fotoId)
                ->where('dokumen_id', $dokumen->id)
                ->update(['order' => $order + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan foto berhasil diupdate!',
        ]);
    }

    /**
     * Get foto untuk dokumen
     */
    public function index(Dokumen $dokumen)
    {
        $user = Auth::user();
        
        // Mitra hanya bisa melihat foto dari dokumennya sendiri
        // Staff bisa melihat foto dari semua dokumen
        if ($user->isMitra() && $dokumen->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat foto dokumen ini.');
        }

        $fotos = $dokumen->fotos()->ordered()->get();

        return response()->json([
            'success' => true,
            'fotos' => $fotos,
            'total_fotos' => $fotos->count(),
        ]);
    }

    /**
     * Download foto
     */
    public function download(Foto $foto)
    {
        $user = Auth::user();
        
        // Mitra hanya bisa download foto dari dokumennya sendiri
        // Staff bisa download semua foto
        if ($user->isMitra() && $foto->dokumen->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk download foto ini.');
        }

        if (!$foto->file_path || !Storage::disk('public')->exists($foto->file_path)) {
            return back()->with('error', 'Foto tidak ditemukan!');
        }

        // Get original filename
        $originalName = $foto->original_name ?? basename($foto->file_path);

        return Storage::disk('public')->download($foto->file_path, $originalName);
    }

    /**
     * Reorder foto setelah penghapusan
     */
    private function reorderFotos($dokumenId)
    {
        $fotos = Foto::where('dokumen_id', $dokumenId)->orderBy('order')->get();
        
        foreach ($fotos as $index => $foto) {
            $foto->update(['order' => $index + 1]);
        }
    }
}
