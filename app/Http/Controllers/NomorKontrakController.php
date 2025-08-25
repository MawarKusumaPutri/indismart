<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class NomorKontrakController extends Controller
{
    /**
     * Display a listing of mitra users with their contract numbers
     */
    public function index()
    {
        // Only karyawan can access this
        if (!Auth::user()->isKaryawan()) {
            abort(403);
        }

        $mitraUsers = User::where('role', 'mitra')
            ->orderBy('name')
            ->paginate(15);

        return view('nomor-kontrak.index', compact('mitraUsers'));
    }

    /**
     * Show the form for assigning contract number to a mitra
     */
    public function assign($id)
    {
        // Only karyawan can access this
        if (!Auth::user()->isKaryawan()) {
            abort(403);
        }

        $mitra = User::where('role', 'mitra')->findOrFail($id);
        
        return view('nomor-kontrak.assign', compact('mitra'));
    }

    /**
     * Store the assigned contract number
     */
    public function store(Request $request, $id)
    {
        // Only karyawan can access this
        if (!Auth::user()->isKaryawan()) {
            abort(403);
        }

        $request->validate([
            'nomor_kontrak' => 'required|string|max:255|unique:users,nomor_kontrak,' . $id,
        ], [
            'nomor_kontrak.required' => 'Nomor kontrak harus diisi',
            'nomor_kontrak.unique' => 'Nomor kontrak sudah digunakan oleh mitra lain',
        ]);

        $mitra = User::where('role', 'mitra')->findOrFail($id);
        $oldNomorKontrak = $mitra->nomor_kontrak;
        
        $mitra->update([
            'nomor_kontrak' => $request->nomor_kontrak
        ]);

        // Send notification to mitra about contract number assignment
        if ($oldNomorKontrak !== $request->nomor_kontrak) {
            NotificationService::notifyUser(
                $mitra->id,
                'Nomor Kontrak Diperbarui',
                'Nomor kontrak Anda telah diperbarui menjadi: ' . $request->nomor_kontrak,
                'info'
            );
        }

        return redirect()->route('nomor-kontrak.index')
            ->with('success', 'Nomor kontrak berhasil diperbarui untuk ' . $mitra->name);
    }

    /**
     * Generate automatic contract number
     */
    public function generate()
    {
        // Only karyawan can access this
        if (!Auth::user()->isKaryawan()) {
            abort(403);
        }

        $prefix = 'KTRK';
        $year = date('Y');
        $month = date('m');
        
        // Get the last contract number for this month
        $lastContract = User::where('nomor_kontrak', 'like', $prefix . $year . $month . '%')
            ->orderBy('nomor_kontrak', 'desc')
            ->first();

        if ($lastContract) {
            // Extract the sequence number and increment
            $lastSequence = (int) substr($lastContract->nomor_kontrak, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        $nomorKontrak = $prefix . $year . $month . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
        
        return response()->json(['nomor_kontrak' => $nomorKontrak]);
    }

    /**
     * Bulk assign contract numbers to mitra without contract numbers
     */
    public function bulkAssign()
    {
        // Only karyawan can access this
        if (!Auth::user()->isKaryawan()) {
            abort(403);
        }

        $mitraWithoutContract = User::where('role', 'mitra')
            ->whereNull('nomor_kontrak')
            ->get();

        $assignedCount = 0;
        $prefix = 'KTRK';
        $year = date('Y');
        $month = date('m');

        foreach ($mitraWithoutContract as $mitra) {
            // Get the last contract number for this month
            $lastContract = User::where('nomor_kontrak', 'like', $prefix . $year . $month . '%')
                ->orderBy('nomor_kontrak', 'desc')
                ->first();

            if ($lastContract) {
                $lastSequence = (int) substr($lastContract->nomor_kontrak, -4);
                $newSequence = $lastSequence + 1;
            } else {
                $newSequence = 1;
            }

            $nomorKontrak = $prefix . $year . $month . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
            
            $mitra->update(['nomor_kontrak' => $nomorKontrak]);
            
                            // Send notification
                try {
                    NotificationService::notifyUser(
                        $mitra->id,
                        'Nomor Kontrak Ditugaskan',
                        'Anda telah ditugaskan nomor kontrak: ' . $nomorKontrak,
                        'info'
                    );
                } catch (\Exception $e) {
                    \Log::warning('Failed to send notification to mitra ' . $mitra->id . ': ' . $e->getMessage());
                }
            
            $assignedCount++;
        }

        return redirect()->route('nomor-kontrak.index')
            ->with('success', $assignedCount . ' nomor kontrak berhasil ditugaskan secara otomatis');
    }


}
