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

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }
}
