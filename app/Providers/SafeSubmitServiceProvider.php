<?php

namespace App\Providers;

use App\SafeSubmit\SafeSubmit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class SafeSubmitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Blade::directive('safeSubmit', function($expression)  {
            return "<?php echo '<input type=\"hidden\" name=\"'. app(SafeSubmit::class)->tokenKey() .'\" value=\"'. app(SafeSubmit::class)->token() .'\">' ?>";
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(SafeSubmit::class, function() {
            return new SafeSubmit();
        });
    }
}
