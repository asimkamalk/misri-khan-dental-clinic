<?php
// app/Console/Commands/SetupApplication.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class SetupApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup {--refresh : Refresh the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the application for first use or refresh it';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting application setup...');

        // Check if database connection is configured
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $this->error('Could not connect to the database. Please check your configuration.');
            return 1;
        }

        // Create storage symbolic link
        $this->info('Creating storage symlink...');
        Artisan::call('storage:link');
        $this->info('Storage symlink created.');

        // Clear caches
        $this->info('Clearing caches...');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        $this->info('Caches cleared.');

        // Check if --refresh option is provided
        if ($this->option('refresh')) {
            if ($this->confirm('This will refresh your database. All data will be lost. Continue?', false)) {
                $this->info('Refreshing database...');
                Artisan::call('migrate:fresh', ['--seed' => true]);
                $this->info('Database refreshed and seeded.');
            }
        } else {
            // Run migrations and seeders
            $this->info('Running migrations...');
            Artisan::call('migrate', ['--force' => true]);

            // Check if the users table is empty
            if (DB::table('users')->count() === 0) {
                $this->info('Seeding database...');
                Artisan::call('db:seed', ['--force' => true]);
                $this->info('Database seeded.');
            } else {
                $this->info('Database already has users. Skipping seeding to prevent duplication.');
            }
        }

        // Create necessary directories
        $this->createDirectories();

        // Set permissions
        $this->setPermissions();

        // Optimize the application for production
        if (app()->environment('production')) {
            $this->info('Optimizing application...');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            $this->info('Application optimized.');
        }

        $this->info('Application setup completed successfully!');
        $this->info('Default admin credentials:');
        $this->info('Email: admin@misrikhandental.com');
        $this->info('Password: Admin@123');
        $this->info('Please change these credentials after first login.');

        return 0;
    }

    /**
     * Create necessary directories.
     *
     * @return void
     */
    protected function createDirectories()
    {
        $this->info('Creating necessary directories...');

        $directories = [
            storage_path('app/public/images'),
            storage_path('app/public/services'),
            storage_path('app/public/doctors'),
            storage_path('app/public/testimonials'),
            storage_path('app/public/settings'),
        ];

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
        }

        $this->info('Directories created.');
    }

    /**
     * Set proper permissions for directories.
     *
     * @return void
     */
    protected function setPermissions()
    {
        $this->info('Setting directory permissions...');

        if (PHP_OS_FAMILY !== 'Windows') {
            exec('chmod -R 775 ' . storage_path());
            exec('chmod -R 775 ' . base_path('bootstrap/cache'));
            $this->info('Permissions set.');
        } else {
            $this->warn('Skipping permission setup on Windows.');
        }
    }
}