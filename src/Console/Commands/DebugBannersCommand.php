<?php

namespace admin\banners\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class DebugBannersCommand extends Command
{
    protected $signature = 'banners:debug';
    protected $description = 'Debug Banners module loading';

    public function handle()
    {
        $this->info('🔍 Debugging Banners Module...');
        
        // Check which route file is being loaded
        $this->info("\n📍 Route Loading Priority:");
        $moduleRoutes = base_path('Modules/Banners/routes/web.php');
        $packageRoutes = base_path('packages/admin/banners/src/routes/web.php');
        
        if (File::exists($moduleRoutes)) {
            $this->info("✅ Module routes found: {$moduleRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($moduleRoutes)));
        } else {
            $this->error("❌ Module routes not found");
        }
        
        if (File::exists($packageRoutes)) {
            $this->info("✅ Package routes found: {$packageRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($packageRoutes)));
        } else {
            $this->error("❌ Package routes not found");
        }
        
        // Check view loading priority
        $this->info("\n👀 View Loading Priority:");
        $viewPaths = [
            'Module views' => base_path('Modules/Banners/resources/views'),
            'Published views' => resource_path('views/admin/banner'),
            'Package views' => base_path('packages/admin/banners/resources/views'),
        ];
        
        foreach ($viewPaths as $name => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$name}: {$path}");
            } else {
                $this->warn("⚠️  {$name}: NOT FOUND - {$path}");
            }
        }
        
        // Check controller resolution
        $this->info("\n🎯 Controller Resolution:");
        $controllerClass = 'Modules\\Banners\\app\\Http\\Controllers\\Admin\\BannerManagerController';
        
        if (class_exists($controllerClass)) {
            $this->info("✅ Controller class found: {$controllerClass}");
            
            $reflection = new \ReflectionClass($controllerClass);
            $this->info("   File: " . $reflection->getFileName());
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($reflection->getFileName())));
        } else {
            $this->error("❌ Controller class not found: {$controllerClass}");
        }
        
        // Show current routes
        $this->info("\n🛣️  Current Routes:");
        $routes = Route::getRoutes();
        $bannerRoutes = [];
        
        foreach ($routes as $route) {
            $action = $route->getAction();
            if (isset($action['controller']) && str_contains($action['controller'], 'BannerManagerController')) {
                $bannerRoutes[] = [
                    'uri' => $route->uri(),
                    'methods' => implode('|', $route->methods()),
                    'controller' => $action['controller'],
                    'name' => $route->getName(),
                ];
            }
        }
        
        if (!empty($bannerRoutes)) {
            $this->table(['URI', 'Methods', 'Controller', 'Name'], $bannerRoutes);
        } else {
            $this->warn("No banner routes found.");
        }
    }
}
