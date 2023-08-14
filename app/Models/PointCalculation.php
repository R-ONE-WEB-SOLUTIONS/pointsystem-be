<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointCalculation extends Model
{
    use HasFactory;

    protected $casts = [
        'multiplier' => 'float',
    ];

    protected $fillable = [
        'base_amount',
        'points_per_base_amount',
        'multiplier'
    ];

    public function Business()
    {
        return $this->belongsTo(Business::class,'business_id');
    }
}
