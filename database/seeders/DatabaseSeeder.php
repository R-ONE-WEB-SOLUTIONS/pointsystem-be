<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\PreReg;
use App\Models\Account;
use App\Models\Business;
use App\Models\UserType;
use App\Models\ClientType;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use App\Models\PointCalculation;
use Illuminate\Support\Facades\Hash;
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

        $business = [
            [
                'business_name' => 'Euphoria',
                'business_address' => 'somewhere',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'business_name' => 'Other Business',
                'business_address' => 'somewhere',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        Business::insert($business);

        $user = [
            [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin@gmail.com'), // Hash the password
                'phone_number' => '09678777939',
                'address' => 'Barra Opol',
                'user_type_id' => 1,
                'business_id' => null,
                'roles' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'first_name' => 'Roi',
                'last_name' => 'Rotoras',
                'email' => 'roi@gmail.com',
                'password' => Hash::make('roi@gmail.com'), // Hash the password
                'phone_number' => '09678777888',
                'address' => 'Balulang',
                'user_type_id' => 2,
                'business_id' => 1,
                'roles' => json_encode(['can_pre_reg']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'first_name' => 'Jim',
                'last_name' => 'Lao',
                'email' => 'lao@gmail.com',
                'password' => Hash::make('lao@gmail.com'), // Hash the password
                'phone_number' => '09678777999',
                'address' => 'Barra Opol',
                'user_type_id' => 2,
                'business_id' => 2,
                'roles' => json_encode(['can_pre_reg', 'can_view_transaction']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        User::insert($user);

        $pre_reg = [
            [
                'first_name' => 'Mark',
                'last_name' => 'Achacoso',
                'middle_name' => '',
                'extension_name' => '',
                'email' => 'mark@gmail.com',
                'phone_number' => '09678777939',
                'address' => 'somewhere',
                'client_type_id' => 1,
                'business_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'first_name' => 'Denzel',
                'last_name' => 'Lanzaderas',
                'middle_name' => '',
                'extension_name' => '',
                'email' => 'denz@gmail.com',
                'phone_number' => '09678777939',
                'address' => 'somewhere',
                'client_type_id' => 2,
                'business_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        PreReg::insert($pre_reg);
        $clients = [
            [
                'first_name' => 'Mark',
                'last_name' => 'Achacoso',
                'middle_name' => '',
                'extension_name' => '',
                'email' => 'mark@gmail.com',
                'phone_number' => '09678777939',
                'address' => 'somewhere',
                'client_type_id' => 1,
                'business_id' => 1,
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'first_name' => 'Denzel',
                'last_name' => 'Lanzaderas',
                'middle_name' => '',
                'extension_name' => '',
                'email' => 'denz@gmail.com',
                'phone_number' => '09678777939',
                'address' => 'somewhere',
                'client_type_id' => 2,
                'business_id' => 2,
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        Client::insert($clients);

        $account = [
            [    
                'account_number' => date("Ymd",time()) .'_'. 100000001,
                'client_id' => 100000001,
                'current_balance' => 0.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [    
                'account_number' => date("Ymd",time()) .'_'. 100000002,
                'client_id' => 100000002,
                'current_balance' => 0.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        
        Account::insert($account);
        

        $point_calculation = [
            [
                'base_amount' => 200.00,
                'points_per_base_amount' => 2,
                'multiplier' => 0.01,
                'business_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'base_amount' => 200.00,
                'points_per_base_amount' => 3,
                'multiplier' => 0.015,
                'business_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        PointCalculation::insert($point_calculation);

        // $transaction = [
        //     [
        //         'reference_id' => 1 . '_' . time(),
        //         'reciept_number' => '123123123123',
        //         'reciept_amount' => 2000.00,
        //         'points' => 20.00,
        //         'user_id' => 2,
        //         'account_id' => 1,
        //         'transaction_type' => 'Reward Points',
        //         'previous_balance' => 0.00,
        //         'void' => false,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'reference_id' => 2 . '_' . time(),
        //         'reciept_number' => '123123123124',
        //         'reciept_amount' => 1000.00,
        //         'points' => 10.00,
        //         'user_id' => 2,
        //         'account_id' => 1,
        //         'transaction_type' => 'Reward Points',
        //         'previous_balance' => 20.00,
        //         'void' => false,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'reference_id' => 3 . '_' . time(),
        //         'reciept_number' => '123123123124',
        //         'reciept_amount' => 1000.00,
        //         'points' => 10.00,
        //         'user_id' => 2,
        //         'account_id' => 2,
        //         'transaction_type' => 'Reward Points',
        //         'previous_balance' => 0.00,
        //         'void' => false,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ]
        // ];
        // Transaction::insert($transaction);
    }
}
