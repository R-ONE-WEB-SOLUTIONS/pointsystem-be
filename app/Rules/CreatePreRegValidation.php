<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
class CreatePreRegValidation implements Rule
{
    private $businessId;

    public function __construct($businessId)
    {
        $this->businessId = $businessId;
    }


    public function passes($attribute, $value)
    {
        if($value){
            $existingEmail = DB::table('pre_regs')
            ->where('email', $value)
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
