<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserType;
use App\Models\ClientType;
use Illuminate\Database\Seeder;
use App\Models\PointCalculation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $clientTypes = [
            [
                'client_type' => 'Basic',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'client_type' => 'Prestige',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        ClientType::insert($clientTypes);

        $userTypes = [
            [
                'user_type' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_type' => 'Cashier',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        UserType::insert($userTypes);

        $user = [
            [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => 'admin@gmail.com',
                'phone_number' => '09678777939',
                'address' => 'Barra Opol',
                'user_type_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        User::insert($user);

        $point_calculation = [
            [
                'base_amount' => 200.00,
                'points_per_base_amount' => 2,
                'multiplier' => 0.01,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        PointCalculation::insert($point_calculation);
    }
}
