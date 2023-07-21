<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'account_number',
        'client_id',
        'current_balance'
    ];

    protected $casts = [
        'current_balance' => 'float',
    ];
}
