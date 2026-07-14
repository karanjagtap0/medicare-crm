<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineImage extends Model
{
    protected $fillable = [
        'medicine_id',
        'image',
        'image_name',
        'sort_order',
        'is_primary',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
