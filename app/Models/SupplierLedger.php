<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'transaction_date', 'debit', 'credit', 'balance', 'remarks'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}
