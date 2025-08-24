<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Blade directives for Indonesian time formatting
        Blade::directive('indonesianDate', function ($expression) {
            return "<?php echo App\Helpers\TimeHelper::formatIndonesianDate($expression); ?>";
        });
        
        Blade::directive('indonesianDateOnly', function ($expression) {
            return "<?php echo App\Helpers\TimeHelper::formatIndonesianDateOnly($expression); ?>";
        });
        
        Blade::directive('indonesianTimeOnly', function ($expression) {
            return "<?php echo App\Helpers\TimeHelper::formatIndonesianTimeOnly($expression); ?>";
        });
    }
}
