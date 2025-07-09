<?php

namespace admin\banners;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BannerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package  
        $this->loadViewsFrom([
            base_path('Modules/Banners/resources/views'), // Published module views first
            resource_path('views/admin/banner'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'banner');
        
        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/Banners/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Banners/resources/views'), 'banners-module');
        }
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Also load migrations from published module if they exist
        if (is_dir(base_path('Modules/Banners/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/Banners/database/migrations'));
        }

        // Only publish automatically during package installation, not on every request
        // Use 'php artisan banners:publish' command for manual publishing
        // $this->publishWithNamespaceTransformation();
        
        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../database/migrations' => base_path('Modules/Banners/database/migrations'),
            __DIR__ . '/../resources/views' => base_path('Modules/Banners/resources/views/'),
        ], 'banner');
       
        $this->registerAdminRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishWithNamespaceTransformation();
        }
    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $admin = DB::table('admins')
            ->orderBy('created_at', 'asc')
            ->first();
            
        $slug = $admin->website_slug ?? 'admin';


        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/routes/web.php');
            });
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\banners\Console\Commands\PublishBannersModuleCommand::class,
                \admin\banners\Console\Commands\CheckModuleStatusCommand::class,
                \admin\banners\Console\Commands\DebugBannersCommand::class,
                \admin\banners\Console\Commands\TestViewResolutionCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../src/Controllers/BannerManagerController.php' => base_path('Modules/Banners/app/Http/Controllers/Admin/BannerManagerController.php'),
            
            // Models
            __DIR__ . '/../src/Models/Banner.php' => base_path('Modules/Banners/app/Models/Banner.php'),
            
            // Requests
            __DIR__ . '/../src/Requests/BannerCreateRequest.php' => base_path('Modules/Banners/app/Http/Requests/BannerCreateRequest.php'),
            __DIR__ . '/../src/Requests/BannerUpdateRequest.php' => base_path('Modules/Banners/app/Http/Requests/BannerUpdateRequest.php'),
            
            // Routes
            __DIR__ . '/routes/web.php' => base_path('Modules/Banners/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));
                
                // Read the source file
                $content = File::get($source);
                
                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);
                
                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    /**
     * Transform namespaces in PHP files
     */
    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\banners\\Controllers;' => 'namespace Modules\\Banners\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\banners\\Models;' => 'namespace Modules\\Banners\\app\\Models;',
            'namespace admin\\banners\\Requests;' => 'namespace Modules\\Banners\\app\\Http\\Requests;',
            
            // Use statements transformations
            'use admin\\banners\\Controllers\\' => 'use Modules\\Banners\\app\\Http\\Controllers\\Admin\\',
            'use admin\\banners\\Models\\' => 'use Modules\\Banners\\app\\Models\\',
            'use admin\\banners\\Requests\\' => 'use Modules\\Banners\\app\\Http\\Requests\\',
            
            // Class references in routes
            'admin\\banners\\Controllers\\BannerManagerController' => 'Modules\\Banners\\app\\Http\\Controllers\\Admin\\BannerManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = $this->transformControllerNamespaces($content);
        } elseif (str_contains($sourceFile, 'Models')) {
            $content = $this->transformModelNamespaces($content);
        } elseif (str_contains($sourceFile, 'Requests')) {
            $content = $this->transformRequestNamespaces($content);
        } elseif (str_contains($sourceFile, 'routes')) {
            $content = $this->transformRouteNamespaces($content);
        }

        return $content;
    }

    /**
     * Transform controller-specific namespaces
     */
    protected function transformControllerNamespaces($content)
    {
        // Update use statements for models and requests
        $content = str_replace(
            'use admin\\banners\\Models\\Banner;',
            'use Modules\\Banners\\app\\Models\\Banner;',
            $content
        );
        
        $content = str_replace(
            'use admin\\banners\\Requests\\BannerCreateRequest;',
            'use Modules\\Banners\\app\\Http\\Requests\\BannerCreateRequest;',
            $content
        );
        
        $content = str_replace(
            'use admin\\banners\\Requests\\BannerUpdateRequest;',
            'use Modules\\Banners\\app\\Http\\Requests\\BannerUpdateRequest;',
            $content
        );

        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        // Any model-specific transformations
        return $content;
    }

    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        // Any request-specific transformations
        return $content;
    }

    /**
     * Transform route-specific namespaces
     */
    protected function transformRouteNamespaces($content)
    {
        // Update controller references in routes
        $content = str_replace(
            'admin\\banners\\Controllers\\BannerManagerController',
            'Modules\\Banners\\app\\Http\\Controllers\\Admin\\BannerManagerController',
            $content
        );

        return $content;
    }
}
