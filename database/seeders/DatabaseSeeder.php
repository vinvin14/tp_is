<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
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
    }
}
