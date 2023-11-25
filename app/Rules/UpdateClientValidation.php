<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
class UpdateClientValidation implements Rule
{
    private $businessId;
    private $id;
    public function __construct($id, $businessId)
    {
        $this->businessId = $businessId;
        $this->id = $id;
    }

    public function passes($attribute, $value)
    {
        if($value){
            $existingEmail = DB::table('clients')
            ->where('email', $value)
            ->where('id', '!=', $this->id)
            ->where('business_id', $this->businessId)
            ->exists();

        return !$existingEmail;
        }
        else{
        return false;
        }

    }

    public function message()
    {
        return 'The email has already been taken for the same business.';
    }
}
