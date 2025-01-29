<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'initial_stock_quantity',
        'current_stock_quantity',
        'category_id'
    ];

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

}
