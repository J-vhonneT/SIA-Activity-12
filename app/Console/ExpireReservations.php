<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class ExpireReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire reservations that have passed their expiration time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired reservations...');

        // For this system, we will delete expired reservations to free up the slot.
        // In a more complex system, you might change a 'status' field instead.
        $expiredCount = Reservation::where('expires_at', '<', Carbon::now())->delete();

        if ($expiredCount > 0) {
            $this->info("Successfully expired and removed {$expiredCount} reservation(s).");
        } else {
            $this->info('No expired reservations found.');
        }

        return 0;
    }
}
