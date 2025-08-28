<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'product_id',
        'quantity',
        'status', // planned, in_progress, completed, cancelled
        'start_date',
        'completion_date',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
