<?php
// app/Console/Commands/SendAppointmentReminders.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AppointmentEmailService;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for tomorrow\'s appointments';

    /**
     * The appointment email service instance.
     *
     * @var \App\Services\AppointmentEmailService
     */
    protected $emailService;

    /**
     * Create a new command instance.
     *
     * @param \App\Services\AppointmentEmailService $emailService
     * @return void
     */
    public function __construct(AppointmentEmailService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Sending appointment reminders...');

        $count = $this->emailService->sendTomorrowReminders();

        $this->info("Sent {$count} appointment reminders.");

        return 0;
    }
}