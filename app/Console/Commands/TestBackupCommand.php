<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class TestBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba el proceso de backup manual';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando prueba de backup manual...');

        // 1. Verificar conexión a base de datos
        try {
            DB::connection()->getPdo();
            $this->info('✓ Conexión a base de datos OK: ' . DB::connection()->getDatabaseName());
        } catch (\Exception $e) {
            $this->error('✗ Error de conexión a base de datos: ' . $e->getMessage());
            return 1;
        }

        // 2. Verificar directorio de backups
        $backupPath = storage_path('app/backups');
        if (!file_exists($backupPath)) {
            $this->info('Creando directorio de backups...');
            mkdir($backupPath, 0755, true);
        }
        $this->info('✓ Directorio de backups: ' . $backupPath);

        // 3. Crear backup manual usando mysqldump (con más opciones)
        $dbConfig = config('database.connections.mysql');
        $filename = 'manual_backup_' . date('Y-m-d_H-i-s');
        $dumpFile = $backupPath . '/' . $filename . '.sql';

        // Comando mejorado con flags adicionales para depurar
        $command = sprintf(
            'mysqldump --verbose --host=%s --port=%s --user=%s %s --result-file=%s %s',
            escapeshellarg($dbConfig['host']),
            escapeshellarg($dbConfig['port']),
            escapeshellarg($dbConfig['username']),
            !empty($dbConfig['password']) ? '--password=' . escapeshellarg($dbConfig['password']) : '',
            escapeshellarg($dumpFile),
            escapeshellarg($dbConfig['database'])
        );

        $this->info('Ejecutando comando: ' . $command);

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(3600);
        $process->run();

        if ($process->isSuccessful()) {
            if (file_exists($dumpFile) && filesize($dumpFile) > 0) {
                $this->info('✓ Backup manual creado: ' . $dumpFile);
                $this->info('✓ Tamaño del archivo: ' . round(filesize($dumpFile) / 1024, 2) . 'KB');

                // Comprimir el archivo SQL a ZIP
                $zipFile = $backupPath . '/' . $filename . '.zip';
                $zip = new \ZipArchive();
                if ($zip->open($zipFile, \ZipArchive::CREATE) === TRUE) {
                    $zip->addFile($dumpFile, basename($dumpFile));
                    $zip->close();
                    $this->info('✓ Archivo comprimido: ' . $zipFile);
                    // Eliminar el archivo SQL original
                    unlink($dumpFile);
                } else {
                    $this->error('✗ Error al comprimir el archivo');
                }
            } else {
                $this->error('✗ El archivo de backup existe pero está vacío');
                // Mostrar el error de mysqldump si hay alguno
                $this->info('Salida del comando: ' . $process->getOutput());
                $this->error('Error del comando: ' . $process->getErrorOutput());
            }
        } else {
            $this->error('✗ Error al crear backup manual');
            $this->error('Código de salida: ' . $process->getExitCode());
            $this->error('Error: ' . $process->getErrorOutput());
            return 1;
        }

        // 4. Verificar las tablas de la base de datos
        $tables = DB::select('SHOW TABLES');
        $this->info('Tablas en la base de datos:');
        foreach ($tables as $table) {
            $tableNameKey = 'Tables_in_' . strtolower($dbConfig['database']);
            $this->info('- ' . $table->$tableNameKey);
        }

        $this->info('Prueba de backup completada');
        return 0;
    }
}
