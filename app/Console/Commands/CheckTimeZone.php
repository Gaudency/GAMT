<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckTimeZone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'time:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica la hora actual según la configuración de zona horaria';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Información de zona horaria del sistema:');
        $this->line('-------------------------------------');
        $this->info('Zona horaria configurada: ' . config('app.timezone'));
        $this->info('Fecha y hora actual: ' . now()->format('Y-m-d H:i:s'));
        $this->info('Hora UTC actual: ' . now()->setTimezone('UTC')->format('Y-m-d H:i:s'));
        $this->info('Diferencia con UTC: ' . now()->tzDifference('UTC'));
        $this->line('-------------------------------------');
        $this->info('PHP date(): ' . date('Y-m-d H:i:s'));
        $this->info('Zona horaria PHP: ' . date_default_timezone_get());
        return Command::SUCCESS;
    }
}
