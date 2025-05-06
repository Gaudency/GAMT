<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        // Opcional: agregar middleware adicional para limitar acceso solo a administradores
        // $this->middleware('admin');
    }

    public function index()
    {
        // Verificar si existe el directorio de backups
        $backupPath = storage_path('app/backups');
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        // Obtener la lista de backups existentes directamente del sistema de archivos
        $backupFiles = glob($backupPath . '/*.zip');

        $backups = collect($backupFiles)
            ->map(function ($file) {
                return [
                    'file_path' => $file,
                    'file_name' => basename($file),
                    'file_size' => filesize($file),
                    'created_at' => filemtime($file),
                ];
            })
            ->sortByDesc('created_at');

        // Log para depuración
        Log::info('Directorio de backups: ' . $backupPath);
        Log::info('Archivos encontrados: ' . count($backups));

        return view('admin.backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            // Verificar la conexión a MySQL antes de proceder
            try {
                \DB::connection()->getPdo();
            } catch (\Exception $e) {
                Log::error('Error de conexión a MySQL: ' . $e->getMessage());
                throw new \Exception('No se puede conectar a MySQL. Por favor, verifica que el servicio esté corriendo.');
            }

            // Crear el directorio de backups si no existe
            $backupPath = storage_path('app/backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            // Configuración de la base de datos
            $dbConfig = config('database.connections.mysql');
            $filename = 'backup-' . date('Y-m-d-H-i-s');
            $dumpFile = $backupPath . '/' . $filename . '.sql';

            // Log de la configuración
            Log::info('Configuración de backup:', [
                'host' => $dbConfig['host'],
                'port' => $dbConfig['port'],
                'database' => $dbConfig['database'],
                'mysqldump_command' => 'mysqldump' // Indicar que se usará el comando del sistema
            ]);

            // Construir el comando mysqldump usando el comando del sistema
            // NOTA: Se asume que mysqldump está en el PATH del servidor Ubuntu
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s %s --result-file=%s %s',
                // No se pasa el path como primer argumento
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['port']),
                escapeshellarg($dbConfig['username']),
                !empty($dbConfig['password']) ? '--password=' . escapeshellarg($dbConfig['password']) : '',
                escapeshellarg($dumpFile),
                escapeshellarg($dbConfig['database'])
            );

            Log::info('Ejecutando comando de backup: ' . $command);

            // Ejecutar el comando
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(3600);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \Exception('Error al ejecutar mysqldump: ' . $process->getErrorOutput());
            }

            if (!file_exists($dumpFile) || filesize($dumpFile) === 0) {
                throw new \Exception('El archivo de backup se creó pero está vacío. Verifica la conexión a la base de datos.');
            }

            // Comprimir el archivo SQL
            $zipFile = $backupPath . '/' . $filename . '.zip';
            $zip = new \ZipArchive();
            if ($zip->open($zipFile, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('No se pudo crear el archivo ZIP');
            }

            $zip->addFile($dumpFile, basename($dumpFile));
            $zip->close();

            // Eliminar el archivo SQL original
            unlink($dumpFile);

            Log::info('Backup creado correctamente: ' . $zipFile);

            return redirect()->route('backups.index')
                ->with('success', 'Backup de la base de datos creado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear backup: ' . $e->getMessage());
            return redirect()->route('backups.index')
                ->with('error', 'Error al crear el backup: ' . $e->getMessage());
        }
    }

    public function download($fileName)
    {
        $filePath = storage_path("app/backups/{$fileName}");

        if (file_exists($filePath)) {
            // Log para depuración
            Log::info('Descargando backup', [
                'file' => $filePath,
                'size' => filesize($filePath)
            ]);

            // Configurar encabezados adecuados para la descarga
            return response()->download(
                $filePath,
                $fileName,
                [
                    'Content-Type' => 'application/zip',
                    'Content-Disposition' => 'attachment; filename="'.$fileName.'"'
                ]
            );
        }

        return redirect()->route('backups.index')
            ->with('error', 'El archivo de backup no existe.');
    }

    public function destroy($fileName)
    {
        $filePath = storage_path("app/backups/{$fileName}");

        if (file_exists($filePath)) {
            unlink($filePath);
            return redirect()->route('backups.index')
                ->with('success', 'Backup eliminado correctamente.');
        }

        return redirect()->route('backups.index')
            ->with('error', 'El archivo de backup no existe.');
    }
}
