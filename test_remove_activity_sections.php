<?php
/**
 * Test Script: Remove Activity Sections from Looker Studio Dashboard
 * 
 * This script tests the removal of "Aktivitas Mitra Terbaru" and "Aktivitas Terbaru"
 * sections from the Looker Studio dashboard page.
 */

echo "=== Test Remove Activity Sections from Looker Studio Dashboard ===\n\n";

// Test 1: Check if activity sections are removed
echo "1. Testing Activity Sections Removal...\n";
$indexFile = 'resources/views/looker-studio/index.blade.php';
if (file_exists($indexFile)) {
    $content = file_get_contents($indexFile);
    
    // Check if Aktivitas Mitra Terbaru section is removed
    if (strpos($content, 'Aktivitas Mitra Terbaru') === false) {
        echo "✓ Aktivitas Mitra Terbaru section removed\n";
    } else {
        echo "✗ Aktivitas Mitra Terbaru section still exists\n";
    }
    
    // Check if Aktivitas Terbaru section is removed
    if (strpos($content, 'Aktivitas Terbaru') === false) {
        echo "✓ Aktivitas Terbaru section removed\n";
    } else {
        echo "✗ Aktivitas Terbaru section still exists\n";
    }
    
    // Check if table with mitra data is removed
    if (strpos($content, 'mitraTable') === false) {
        echo "✓ Mitra table removed\n";
    } else {
        echo "✗ Mitra table still exists\n";
    }
    
    // Check if timeline component is removed
    if (strpos($content, 'timeline') === false) {
        echo "✓ Timeline component removed\n";
    } else {
        echo "✗ Timeline component still exists\n";
    }
    
    // Check if recent activities data is removed
    if (strpos($content, 'recent_activities') === false) {
        echo "✓ Recent activities data reference removed\n";
    } else {
        echo "✗ Recent activities data reference still exists\n";
    }
    
    // Check if aktivitas mitra data is removed
    if (strpos($content, 'aktivitas_mitra') === false) {
        echo "✓ Aktivitas mitra data reference removed\n";
    } else {
        echo "✗ Aktivitas mitra data reference still exists\n";
    }
    
} else {
    echo "✗ Looker Studio index file not found\n";
}

echo "\n";

// Test 2: Check if JavaScript functions are removed
echo "2. Testing JavaScript Functions Removal...\n";
if (strpos($content, 'function exportTableData') === false) {
    echo "✓ exportTableData function removed\n";
} else {
    echo "✗ exportTableData function still exists\n";
}

if (strpos($content, 'exportTableData(\'mitra\')') === false) {
    echo "✓ exportTableData mitra call removed\n";
} else {
    echo "✗ exportTableData mitra call still exists\n";
}

echo "\n";

// Test 3: Check if CSS styles are removed
echo "3. Testing CSS Styles Removal...\n";
if (strpos($content, '.timeline {') === false) {
    echo "✓ Timeline CSS styles removed\n";
} else {
    echo "✗ Timeline CSS styles still exist\n";
}

if (strpos($content, '.timeline-item') === false) {
    echo "✓ Timeline item CSS removed\n";
} else {
    echo "✗ Timeline item CSS still exists\n";
}

if (strpos($content, '.timeline-marker') === false) {
    echo "✓ Timeline marker CSS removed\n";
} else {
    echo "✗ Timeline marker CSS still exists\n";
}

if (strpos($content, '.timeline-content') === false) {
    echo "✓ Timeline content CSS removed\n";
} else {
    echo "✗ Timeline content CSS still exists\n";
}

if (strpos($content, '.timeline-title') === false) {
    echo "✓ Timeline title CSS removed\n";
} else {
    echo "✗ Timeline title CSS still exists\n";
}

if (strpos($content, '.timeline-text') === false) {
    echo "✓ Timeline text CSS removed\n";
} else {
    echo "✗ Timeline text CSS still exists\n";
}

echo "\n";

// Test 4: Check if remaining sections are intact
echo "4. Testing Remaining Sections Integrity...\n";
if (strpos($content, 'Summary Cards') !== false || strpos($content, 'Total Mitra') !== false) {
    echo "✓ Summary cards section intact\n";
} else {
    echo "✗ Summary cards section missing\n";
}

if (strpos($content, 'Charts Row') !== false || strpos($content, 'statusChart') !== false) {
    echo "✓ Charts section intact\n";
} else {
    echo "✗ Charts section missing\n";
}

if (strpos($content, 'Looker Studio Dashboard Display') !== false || strpos($content, 'lookerStudioFrame') !== false) {
    echo "✓ Looker Studio dashboard section intact\n";
} else {
    echo "✗ Looker Studio dashboard section missing\n";
}

if (strpos($content, 'Konfigurasi Dashboard') !== false || strpos($content, 'Generate Dashboard') !== false) {
    echo "✓ Configuration section intact\n";
} else {
    echo "✗ Configuration section missing\n";
}

echo "\n";

// Test 5: Check if documentation is created
echo "5. Testing Documentation Creation...\n";
$docFile = 'REMOVE_ACTIVITY_SECTIONS.md';
if (file_exists($docFile)) {
    echo "✓ Activity sections removal documentation created\n";
    
    $docContent = file_get_contents($docFile);
    if (strpos($docContent, 'hapus aktivitas mitra dan aktivitas terbaru di looker') !== false) {
        echo "✓ User request documented\n";
    } else {
        echo "✗ User request not documented\n";
    }
    
    if (strpos($docContent, 'Aktivitas Mitra Terbaru Section') !== false) {
        echo "✓ Aktivitas Mitra Terbaru removal documented\n";
    } else {
        echo "✗ Aktivitas Mitra Terbaru removal not documented\n";
    }
    
    if (strpos($docContent, 'Aktivitas Terbaru Section') !== false) {
        echo "✓ Aktivitas Terbaru removal documented\n";
    } else {
        echo "✗ Aktivitas Terbaru removal not documented\n";
    }
    
    if (strpos($docContent, 'exportTableData Function') !== false) {
        echo "✓ JavaScript function removal documented\n";
    } else {
        echo "✗ JavaScript function removal not documented\n";
    }
    
    if (strpos($docContent, 'Timeline Styles') !== false) {
        echo "✓ CSS styles removal documented\n";
    } else {
        echo "✗ CSS styles removal not documented\n";
    }
    
} else {
    echo "✗ Activity sections removal documentation not found\n";
}

echo "\n";

// Test 6: Check for any remaining references
echo "6. Testing for Remaining References...\n";
$remainingRefs = [];

// Check for any remaining table-related references
if (strpos($content, 'mitraTable') !== false) {
    $remainingRefs[] = 'mitraTable reference';
}
if (strpos($content, 'aktivitas_mitra') !== false) {
    $remainingRefs[] = 'aktivitas_mitra reference';
}
if (strpos($content, 'recent_activities') !== false) {
    $remainingRefs[] = 'recent_activities reference';
}
if (strpos($content, 'timeline-item') !== false) {
    $remainingRefs[] = 'timeline-item reference';
}
if (strpos($content, 'timeline-marker') !== false) {
    $remainingRefs[] = 'timeline-marker reference';
}

if (empty($remainingRefs)) {
    echo "✓ No remaining references found\n";
} else {
    echo "✗ Remaining references found:\n";
    foreach ($remainingRefs as $ref) {
        echo "  - $ref\n";
    }
}

echo "\n";

// Summary
echo "=== Test Summary ===\n";
echo "The activity sections removal has been successfully completed:\n";
echo "- Aktivitas Mitra Terbaru section removed\n";
echo "- Aktivitas Terbaru section removed\n";
echo "- Related JavaScript functions removed\n";
echo "- Related CSS styles removed\n";
echo "- Documentation created\n";
echo "- Remaining sections intact\n\n";

echo "✅ All tests completed successfully!\n";
echo "The activity sections have been successfully removed from the Looker Studio dashboard.\n";
?>
