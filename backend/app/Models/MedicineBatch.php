<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineBatch extends Model
{
    protected $fillable = [
        'medicine_id',
        'batch_number',
        'manufacturing_date',
        'expiry_date',
        'purchase_price',
        'selling_price',
        'quantity_received',
        'available_quantity',
        'barcode',
        'status',
        'created_by',
        'updated_by'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
