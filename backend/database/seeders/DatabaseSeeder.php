<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'yashsonare64@gmail.com',
            'password' => Hash::make('yash123'),
        ]);
        \Artisan::call('csv:import-all');
    }
}