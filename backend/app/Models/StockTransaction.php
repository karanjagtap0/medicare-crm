<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransaction extends Model
{
    protected $fillable = [
        'medicine_id',
        'medicine_batch_id',
        'transaction_type',
        'quantity',
        'previous_stock',
        'current_stock',
        'reference_no',
        'remarks',
        'created_by',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function medicineBatch()
    {
        return $this->belongsTo(MedicineBatch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
