<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MedicineBatch;
use Carbon\Carbon;

class UpdateExpiredMedicineStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medicine:update-expired-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update the status of expired medicine batches to Expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();
        
        $updatedCount = MedicineBatch::where('expiry_date', '<', $today)
            ->where('status', '!=', 'Expired')
            ->update(['status' => 'Expired']);
            
        $this->info("Updated {$updatedCount} expired medicine batches.");
    }
}
