<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function details() // Rename to orderItems if preferred
    {
        return $this->hasMany(Orderdetails::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    // app/Models/Order.php
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];
}
