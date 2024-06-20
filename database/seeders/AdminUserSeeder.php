<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'sharpe',
            'email' => 'sharpe@example.com',
            'password' => Hash::make('password'),
            'database_name' => \Illuminate\Support\Str::random(10),
        ]);
    }
}
