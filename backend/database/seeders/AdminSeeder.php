<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::updateOrCreate(
            ['email' => 'omdeshmukh399@gmail.com'],
            ['password' => Hash::make('om123')]
        );

        Admin::updateOrCreate(
            ['email' => 'yashsonare64@gmail.com'],
            ['password' => Hash::make('yash123')]
        );
    }
}