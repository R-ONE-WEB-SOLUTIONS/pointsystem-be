<?php

namespace App\Models;

use App\Models\User;
use App\Models\Client;
use App\Models\PreReg;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'business_address'
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function preRegs()
    {
        return $this->hasMany(PreReg::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'business_id', 'id');
    }
}
