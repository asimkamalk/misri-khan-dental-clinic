<?php
// app/Console/Commands/ClearLogs.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Laravel log files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $logPath = storage_path('logs');

        if (File::exists($logPath)) {
            $logFiles = File::glob($logPath . '/*.log');
            $count = count($logFiles);

            // Keep the current day's log file
            $currentLogFile = $logPath . '/laravel-' . date('Y-m-d') . '.log';

            foreach ($logFiles as $logFile) {
                // Skip the current day's log file
                if ($logFile !== $currentLogFile) {
                    File::delete($logFile);
                }
            }

            $this->info('Cleared ' . ($count - 1) . ' log files.');
        } else {
            $this->info('No logs found to clear.');
        }

        return 0;
    }
}