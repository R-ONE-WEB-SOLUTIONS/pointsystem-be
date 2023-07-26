<?php

namespace App\Models;

use App\Models\Business;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'business_id',
        'active'
    ];

    public function clientType()
    {
        return $this->belongsTo(ClientType::class);
    }

    public function Business()
    {
        return $this->belongsTo(Business::class);
    }
}
