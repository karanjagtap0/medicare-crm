<?php

namespace App\Services\Medicine;

use App\Models\MedicineBatch;
use Carbon\Carbon;

class ExpiryService
{
    public function getDashboardSummary()
    {
        $today = Carbon::today()->toDateString();
        $ninetyDaysFromNow = Carbon::today()->addDays(90)->toDateString();

        return [
            'total_expired' => MedicineBatch::where('expiry_date', '<', $today)->count(),
            'total_expiring_soon' => MedicineBatch::whereBetween('expiry_date', [$today, $ninetyDaysFromNow])->count(),
            'total_active' => MedicineBatch::where('status', 'Active')->count(),
            'total_blocked' => MedicineBatch::where('status', 'Blocked')->count(),
        ];
    }

    public function getExpiredBatches(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        $today = Carbon::today()->toDateString();
        
        return MedicineBatch::with('medicine')
            ->where('expiry_date', '<', $today)
            ->orderBy('expiry_date', 'asc')
            ->paginate($perPage);
    }

    public function getExpiringBatches(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        $days = $filters['days'] ?? 90;
        $today = Carbon::today()->toDateString();
        $thresholdDate = Carbon::today()->addDays((int)$days)->toDateString();
        
        return MedicineBatch::with('medicine')
            ->whereBetween('expiry_date', [$today, $thresholdDate])
            ->where('status', 'Active')
            ->orderBy('expiry_date', 'asc')
            ->paginate($perPage);
    }

    public function getBatchDetails(int $id)
    {
        return MedicineBatch::with('medicine')->findOrFail($id);
    }

    public function blockBatch(int $id)
    {
        $batch = MedicineBatch::findOrFail($id);
        $batch->update(['status' => 'Blocked']);
        return $batch;
    }
}
