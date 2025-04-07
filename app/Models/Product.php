<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Binafy\LaravelCart\Cartable;

class Product extends Model implements Cartable
{
    use HasFactory;
    protected $guarded = [];

    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function supllier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(Orderdetails::class, 'product_id');
    }
    public function getPrice(): float
    {
        return (float) $this->selling_price*100;
    }
    public function decrementStock($quantity)
    {
        $this->decrement('product_store', $quantity);
    }

    public function incrementStock($quantity)
    {
        $this->increment('product_store', $quantity);
    }
}

