<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerFollowup extends Model
{
    protected $fillable = [
        'customer_id',
        'followup_date',
        'type',
        'remarks',
        'status'
    ];

    protected $casts = [
        'followup_date' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
