<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [

        'medicine_code',
        'sku',

        'name',
        'generic_name',
        'description',

        'category_id',
        'brand_id',
        'supplier_id',
        'tax_id',
        'unit_of_measure_id',

        'purchase_price',
        'selling_price',

        'minimum_stock',
        'maximum_stock',

        'prescription_required',
        'status',

        'created_by',
        'updated_by',
    ];

    // Relationships

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function unitOfMeasure()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_of_measure_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }


    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeBySearch($query, $keyword = null)
    {
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('generic_name', 'LIKE', "%$keyword%")
                    ->orWhere('medicine_code', 'LIKE', "%$keyword%")
                    ->orWhere('sku', 'LIKE', "%$keyword%");
            });
        }
        return $query;
    }


    public function isPrescriptionRequired(): bool
    {
        return $this->prescription_required;
    }

    public function isStatusActive(): bool
    {
        return $this->status;
    }
}
