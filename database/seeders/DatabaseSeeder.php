<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\UserType;
use App\Models\ClientType;
use Illuminate\Database\Seeder;
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
    }
}
