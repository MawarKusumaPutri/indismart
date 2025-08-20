<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Dokumen;

echo "=== TESTING SEARCH INPUT FUNCTIONALITY ===\n\n";

try {
    // Test search dengan berbagai term
    $testTerms = ['Survey', 'Audit', 'Surabaya', 'reza', '1234567', 'test'];
    
    foreach ($testTerms as $term) {
        echo "Testing search term: '$term'\n";
        
        $results = Dokumen::where(function($q) use ($term) {
            $q->where('nama_dokumen', 'like', '%' . $term . '%')
              ->orWhere('jenis_proyek', 'like', '%' . $term . '%')
              ->orWhere('nomor_kontrak', 'like', '%' . $term . '%')
              ->orWhere('witel', 'like', '%' . $term . '%')
              ->orWhere('sto', 'like', '%' . $term . '%')
              ->orWhere('site_name', 'like', '%' . $term . '%')
              ->orWhere('status_implementasi', 'like', '%' . $term . '%')
              ->orWhere('keterangan', 'like', '%' . $term . '%')
              ->orWhereHas('user', function($userQuery) use ($term) {
                  $userQuery->where('name', 'like', '%' . $term . '%');
              });
        })->get();
        
        echo "  Found: " . $results->count() . " results\n";
        
        if ($results->count() > 0) {
            foreach ($results as $doc) {
                echo "    - {$doc->nama_dokumen} ({$doc->jenis_proyek}) by {$doc->user->name}\n";
            }
        }
        echo "\n";
    }
    
    // Test partial search
    echo "Testing partial search:\n";
    $partialTerms = ['Sur', 'Aud', 'Sura', 'rez', '123'];
    
    foreach ($partialTerms as $term) {
        $results = Dokumen::where(function($q) use ($term) {
            $q->where('nama_dokumen', 'like', '%' . $term . '%')
              ->orWhere('jenis_proyek', 'like', '%' . $term . '%')
              ->orWhere('nomor_kontrak', 'like', '%' . $term . '%')
              ->orWhere('witel', 'like', '%' . $term . '%')
              ->orWhere('sto', 'like', '%' . $term . '%')
              ->orWhere('site_name', 'like', '%' . $term . '%')
              ->orWhere('status_implementasi', 'like', '%' . $term . '%')
              ->orWhere('keterangan', 'like', '%' . $term . '%')
              ->orWhereHas('user', function($userQuery) use ($term) {
                  $userQuery->where('name', 'like', '%' . $term . '%');
              });
        })->get();
        
        echo "  '$term': " . $results->count() . " results\n";
    }
    
    echo "\n=== SEARCH INPUT TEST COMPLETED ===\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} 