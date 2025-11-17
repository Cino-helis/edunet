<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ParametreController extends Controller
{
    public function index()
    {
        $parametres = [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'mail_mailer' => config('mail.default'),
        ];

        // Taille du cache
        $cacheSize = $this->getCacheSize();
        
        // Espace disque
        $totalSpace = disk_total_space('/');
        $freeSpace = disk_free_space('/');
        $usedSpace = $totalSpace - $freeSpace;


        $diskSpace = [
        'total' => $this->formatBytes($totalSpace),
        'free' => $this->formatBytes($freeSpace),
        'used' => $this->formatBytes($usedSpace),
        'total_raw' => $totalSpace,
        'used_raw' => $usedSpace,
     ];

        return view('admin.parametres', compact('parametres', 'cacheSize', 'diskSpace'));
    }

    /**
     * Vider le cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return redirect()->route('admin.parametres')
                ->with('success', 'Cache vidé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du nettoyage du cache');
        }
    }

    /**
     * Optimiser l'application
     */
    public function optimize()
    {
        try {
            Artisan::call('optimize');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            return redirect()->route('admin.parametres')
                ->with('success', 'Application optimisée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'optimisation');
        }
    }

    /**
     * Sauvegarder la base de données
     */
    public function backup()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                storage_path('app/backups/' . $filename)
            );

            // Créer le dossier backups s'il n'existe pas
            if (!Storage::exists('backups')) {
                Storage::makeDirectory('backups');
            }

            exec($command);

            return redirect()->route('admin.parametres')
                ->with('success', 'Sauvegarde créée avec succès : ' . $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la sauvegarde');
        }
    }

    /**
     * Obtenir la taille du cache
     */
    private function getCacheSize()
    {
        $path = storage_path('framework/cache');
        $size = 0;

        if (is_dir($path)) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file) {
                $size += $file->getSize();
            }
        }

        return $this->formatBytes($size);
    }

    /**
     * Formater la taille en bytes
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}