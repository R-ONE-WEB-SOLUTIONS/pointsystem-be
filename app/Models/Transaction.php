<?php

namespace App\Models;

use App\Models\Account;
use App\Models\VoidReason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    // public function AccountID()
    // {
    //     return $this->belongsTo(Account::class);
    // }

    public function UserID()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function voidReason()
    {
        return $this->hasMany(VoidReason::class);
    }


}
