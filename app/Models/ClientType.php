<?php

namespace App\Models;

use App\Models\Client;
use App\Models\PreReg;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientType extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_type'
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function preRegs()
    {
        return $this->hasMany(PreReg::class);
    }
}
