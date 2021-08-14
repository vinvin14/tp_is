<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ClaimType;
use App\Models\PaymentMethod;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::query()
            ->create([
                'username' => 'admin',
                'password' => Hash::make('1234'),
                'role' => 'admin',
                'account_owner' => 'Kelvin Valdez',
            ]);

        $units = [
            [
                'name' => 'piece'
            ],
            [
                'name' => 'box'
            ],
            [
                'name' => 'bottle'
            ],
        ];
        foreach ($units as $unit) {
            Unit::query()
                ->create($unit);
        }

        $categories = [
            [
                'name' => 'Beverage'
            ],
            [
                'name' => 'Delicacy'
            ],
            [
                'name' => 'Meat'
            ],
        ];
        foreach ($categories as $category) {
            Category::query()
                ->create($category);
        }

        $paymentMethods = [
            [
                'name' => 'cash'
            ],
            [
                'name' => 'bank transfer'
            ],
            [
                'name' => 'Gcash'
            ]
            ];
        foreach ($paymentMethods as $paymentMethod)
        {
            PaymentMethod::query()
            ->create($paymentMethod);
        }

        $claimTypes = [
            [
                'name' => 'pick up'
            ],
            [
                'name' => 'deliver'
            ]
            ];

         foreach ($claimTypes as $claimType)
         {
             ClaimType::query()
             ->create($claimType);
         }   
    }
}
