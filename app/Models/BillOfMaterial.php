<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', // finished product
        'component_id', // component/raw material
        'quantity',
        'unit_cost',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function component()
    {
        return $this->belongsTo(Product::class, 'component_id');
    }
}
