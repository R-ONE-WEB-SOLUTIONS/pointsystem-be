<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'extension_name',
        'email',
        'phone_number',
        'address',
        'client_type_id',
        'active'
    ];

    public function clientType()
    {
        return $this->belongsTo(ClientType::class);
    }
}
