<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_code',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'dob',
        'gender',
        'status',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // public function prescriptions()
    // {
    //     return $this->hasMany(Prescription::class);
    // }

    // public function sales()
    // {
    //     return $this->hasMany(Sale::class);
    // }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function notes()
    {
        return $this->hasMany(CustomerNote::class);
    }

    public function followups()
    {
        return $this->hasMany(CustomerFollowup::class);
    }

    public function communications()
    {
        return $this->hasMany(CustomerCommunication::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }
}
