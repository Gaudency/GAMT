<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class DiagnosticCommand extends Command
{
    protected $signature = 'diagnostic:mysql';
    protected $description = 'Diagnostica problemas con MySQL y mysqldump';

    public function handle()
    {
        $this->info('Ejecutando diagnóstico de MySQL...');

        // 1. Verificar conexión a la base de datos
        try {
            DB::connection()->getPdo();
            $this->info('✓ Conexión a la base de datos OK: ' . DB::connection()->getDatabaseName());

            // Verificar tablas
            $tables = DB::select('SHOW TABLES');
            $this->info('Tablas en la base de datos (' . count($tables) . '):');
            $tableNameKey = 'Tables_in_' . strtolower(config('database.connections.mysql.database'));
            foreach ($tables as $table) {
                $this->info('- ' . $table->$tableNameKey);
            }
        } catch (\Exception $e) {
            $this->error('✗ Error de conexión a la base de datos: ' . $e->getMessage());
        }

        // 2. Verificar la instalación de mysqldump
        $this->info('Verificando instalación de mysqldump...');

        // Rutas posibles para mysqldump
        $possiblePaths = [
            'mysqldump',
            'C:\xampp\mysql\bin\mysqldump.exe',
            'D:\xampp\mysql\bin\mysqldump.exe',
            '/xampp/mysql/bin/mysqldump'
        ];

        $foundMysqldump = false;
        foreach ($possiblePaths as $path) {
            $process = Process::fromShellCommandline($path . ' --version');
            $process->run();

            if ($process->isSuccessful()) {
                $this->info('✓ mysqldump encontrado en: ' . $path);
                $this->info('  Versión: ' . trim($process->getOutput()));
                $foundMysqldump = true;

                // Hacer una prueba simple
                $testCmd = sprintf(
                    '%s --no-data --host=%s --user=%s %s information_schema',
                    $path,
                    escapeshellarg(config('database.connections.mysql.host')),
                    escapeshellarg(config('database.connections.mysql.username')),
                    !empty(config('database.connections.mysql.password')) ? '--password=' . escapeshellarg(config('database.connections.mysql.password')) : ''
                );

                $this->info('  Ejecutando prueba simple: ' . $testCmd);
                $testProcess = Process::fromShellCommandline($testCmd);
                $testProcess->run();

                if ($testProcess->isSuccessful()) {
                    $this->info('  ✓ Prueba simple exitosa');
                } else {
                    $this->error('  ✗ Prueba simple falló: ' . $testProcess->getErrorOutput());
                }

                break;
            }
        }

        if (!$foundMysqldump) {
            $this->error('✗ No se encontró mysqldump en ninguna de las rutas estándar');
            $this->info('  Sugerencias:');
            $this->info('  - Asegúrate de que XAMPP está instalado correctamente');
            $this->info('  - Agrega la ruta del directorio bin de MySQL a tu PATH');
        }

        return 0;
    }
}