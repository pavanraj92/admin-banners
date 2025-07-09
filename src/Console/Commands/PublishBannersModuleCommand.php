<?php

namespace admin\banners\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishBannersModuleCommand extends Command
{
    protected $signature = 'banners:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish Banners module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing Banners module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/Banners');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'banner',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('Banners module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__)); // Go up to packages/admin/banners/src
        
        $filesWithNamespaces = [
            // Controllers
            $basePath . '/Controllers/BannerManagerController.php' => base_path('Modules/Banners/app/Http/Controllers/Admin/BannerManagerController.php'),
            
            // Models
            $basePath . '/Models/Banner.php' => base_path('Modules/Banners/app/Models/Banner.php'),
            
            // Requests
            $basePath . '/Requests/BannerCreateRequest.php' => base_path('Modules/Banners/app/Http/Requests/BannerCreateRequest.php'),
            $basePath . '/Requests/BannerUpdateRequest.php' => base_path('Modules/Banners/app/Http/Requests/BannerUpdateRequest.php'),
            
            // Routes
            $basePath . '/routes/web.php' => base_path('Modules/Banners/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }

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
            $content = str_replace('use admin\\banners\\Models\\Banner;', 'use Modules\\Banners\\app\\Models\\Banner;', $content);
            $content = str_replace('use admin\\banners\\Requests\\BannerCreateRequest;', 'use Modules\\Banners\\app\\Http\\Requests\\BannerCreateRequest;', $content);
            $content = str_replace('use admin\\banners\\Requests\\BannerUpdateRequest;', 'use Modules\\Banners\\app\\Http\\Requests\\BannerUpdateRequest;', $content);
        }

        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\Banners\\'])) {
            $composer['autoload']['psr-4']['Modules\\Banners\\'] = 'Modules/Banners/app/';
            
            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}
