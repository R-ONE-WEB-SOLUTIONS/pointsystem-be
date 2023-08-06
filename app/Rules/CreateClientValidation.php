<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CreateClientValidation implements Rule
{
    private $businessId;

    public function __construct($businessId)
    {
        $this->businessId = $businessId;
    }

    public function passes($attribute, $value)
    {
        $existingEmail = DB::table('clients')
            ->where('email', $value)
            ->where('business_id', $this->businessId)
            ->exists();

        return !$existingEmail;
    }

    public function message()
    {
        return 'The email has already been taken for the same business.';
    }
}
