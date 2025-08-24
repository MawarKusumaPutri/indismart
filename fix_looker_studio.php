<?php

/**
 * Looker Studio Auto-Fix Script
 * 
 * Script ini akan mengecek dan memperbaiki masalah umum pada fitur Looker Studio
 * 
 * Usage: php fix_looker_studio.php
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class LookerStudioFixer
{
    private $errors = [];
    private $fixes = [];

    public function run()
    {
        echo "🔧 Looker Studio Auto-Fix Script\n";
        echo "================================\n\n";

        $this->checkLaravelEnvironment();
        $this->checkDatabase();
        $this->checkModels();
        $this->checkControllers();
        $this->checkViews();
        $this->checkRoutes();
        $this->checkMiddleware();
        $this->checkDependencies();
        $this->clearCaches();
        $this->generateSummary();

        echo "\n✅ Auto-fix selesai!\n";
    }

    private function checkLaravelEnvironment()
    {
        echo "📋 Checking Laravel Environment...\n";
        
        // Check if .env exists
        if (!file_exists('.env')) {
            $this->errors[] = ".env file not found";
            $this->fixes[] = "Copy .env.example to .env";
        }

        // Check APP_KEY
        if (empty(env('APP_KEY'))) {
            $this->errors[] = "APP_KEY not set";
            $this->fixes[] = "Run: php artisan key:generate";
        }

        echo "   Laravel Environment: " . (empty($this->errors) ? "✅ OK" : "❌ Issues found") . "\n\n";
    }

    private function checkDatabase()
    {
        echo "🗄️  Checking Database...\n";
        
        try {
            // Check database connection
            DB::connection()->getPdo();
            
            // Check required tables
            $requiredTables = ['users', 'dokumen', 'fotos', 'reviews', 'notifications'];
            
            foreach ($requiredTables as $table) {
                if (!Schema::hasTable($table)) {
                    $this->errors[] = "Table '{$table}' not found";
                    $this->fixes[] = "Run: php artisan migrate";
                }
            }

            // Check if users table has data
            $userCount = DB::table('users')->count();
            if ($userCount == 0) {
                $this->errors[] = "No users found in database";
                $this->fixes[] = "Run: php artisan db:seed";
            }

            echo "   Database: ✅ OK\n";
            
        } catch (Exception $e) {
            $this->errors[] = "Database connection failed: " . $e->getMessage();
            $this->fixes[] = "Check database configuration in .env";
            echo "   Database: ❌ Connection failed\n";
        }
        
        echo "\n";
    }

    private function checkModels()
    {
        echo "📦 Checking Models...\n";
        
        $requiredModels = [
            'App\Models\User',
            'App\Models\Dokumen', 
            'App\Models\Foto',
            'App\Models\Review',
            'App\Models\Notification'
        ];

        foreach ($requiredModels as $model) {
            if (!class_exists($model)) {
                $this->errors[] = "Model {$model} not found";
                $this->fixes[] = "Check if {$model} exists in app/Models/";
            }
        }

        echo "   Models: " . (empty($this->errors) ? "✅ OK" : "❌ Issues found") . "\n\n";
    }

    private function checkControllers()
    {
        echo "🎮 Checking Controllers...\n";
        
        $requiredControllers = [
            'App\Http\Controllers\LookerStudioController',
            'App\Http\Controllers\Api\LookerStudioApiController'
        ];

        foreach ($requiredControllers as $controller) {
            if (!class_exists($controller)) {
                $this->errors[] = "Controller {$controller} not found";
                $this->fixes[] = "Check if {$controller} exists";
            }
        }

        echo "   Controllers: " . (empty($this->errors) ? "✅ OK" : "❌ Issues found") . "\n\n";
    }

    private function checkViews()
    {
        echo "👁️  Checking Views...\n";
        
        $requiredViews = [
            'resources/views/looker-studio/index.blade.php',
            'resources/views/layouts/app.blade.php'
        ];

        foreach ($requiredViews as $view) {
            if (!file_exists($view)) {
                $this->errors[] = "View {$view} not found";
                $this->fixes[] = "Check if {$view} exists";
            }
        }

        echo "   Views: " . (empty($this->errors) ? "✅ OK" : "❌ Issues found") . "\n\n";
    }

    private function checkRoutes()
    {
        echo "🛣️  Checking Routes...\n";
        
        // Check if routes are registered
        $routes = [
            'looker-studio.index',
            'looker-studio.generate',
            'looker-studio.export'
        ];

        foreach ($routes as $route) {
            try {
                route($route);
            } catch (Exception $e) {
                $this->errors[] = "Route '{$route}' not found";
                $this->fixes[] = "Check routes/web.php for Looker Studio routes";
            }
        }

        echo "   Routes: " . (empty($this->errors) ? "✅ OK" : "❌ Issues found") . "\n\n";
    }

    private function checkMiddleware()
    {
        echo "🔒 Checking Middleware...\n";
        
        // Check if role middleware exists
        $middlewarePath = 'app/Http/Middleware/RoleMiddleware.php';
        if (!file_exists($middlewarePath)) {
            $this->errors[] = "RoleMiddleware not found";
            $this->fixes[] = "Check if RoleMiddleware exists in app/Http/Middleware/";
        }

        echo "   Middleware: " . (empty($this->errors) ? "✅ OK" : "❌ Issues found") . "\n\n";
    }

    private function checkDependencies()
    {
        echo "📚 Checking Dependencies...\n";
        
        // Check if composer.json has required packages
        if (file_exists('composer.json')) {
            $composer = json_decode(file_get_contents('composer.json'), true);
            $requiredPackages = ['laravel/framework'];
            
            foreach ($requiredPackages as $package) {
                if (!isset($composer['require'][$package])) {
                    $this->errors[] = "Package {$package} not found in composer.json";
                    $this->fixes[] = "Run: composer install";
                }
            }
        }

        echo "   Dependencies: " . (empty($this->errors) ? "✅ OK" : "❌ Issues found") . "\n\n";
    }

    private function clearCaches()
    {
        echo "🧹 Clearing Caches...\n";
        
        try {
            // Clear various caches
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            echo "   Caches cleared: ✅ OK\n";
        } catch (Exception $e) {
            $this->errors[] = "Failed to clear caches: " . $e->getMessage();
            echo "   Caches: ❌ Failed\n";
        }
        
        echo "\n";
    }

    private function generateSummary()
    {
        echo "📊 Summary:\n";
        echo "===========\n";
        
        if (empty($this->errors)) {
            echo "✅ No issues found! Looker Studio should work properly.\n";
        } else {
            echo "❌ Found " . count($this->errors) . " issue(s):\n\n";
            
            foreach ($this->errors as $index => $error) {
                echo ($index + 1) . ". " . $error . "\n";
                if (isset($this->fixes[$index])) {
                    echo "   Fix: " . $this->fixes[$index] . "\n";
                }
                echo "\n";
            }
            
            echo "🔧 Recommended Actions:\n";
            echo "======================\n";
            
            foreach (array_unique($this->fixes) as $fix) {
                echo "• " . $fix . "\n";
            }
        }
    }
}

// Run the fixer
$fixer = new LookerStudioFixer();
$fixer->run();
