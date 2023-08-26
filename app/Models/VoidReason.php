<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoidReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason_for_voiding',
        'transaction_id',
        'voiding_user'
    ];

    public function transactions()
    {
        return $this->belongsTo(Transaction::class, 'transactions_id');
    }
}
