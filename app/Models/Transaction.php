<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'reciept_number',
        'reciept_amount',
        'points',
        'user_id',
        'account_id',
        'transaction_type',
        'previous_balance',
        'void'
    ];
}
