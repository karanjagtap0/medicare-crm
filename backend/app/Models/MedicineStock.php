<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineStock extends Model
{
    protected $fillable = [
        'medicine_id',
        'medicine_batch_id',
        'current_quantity',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function medicineBatch()
    {
        return $this->belongsTo(MedicineBatch::class);
    }
}
