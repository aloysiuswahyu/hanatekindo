<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            [
                'name' => 'Super',
                'email' => 'super@admin.test',
                'password' => 'sss',
            ]
        );
        $user->password = Hash::make('h4n4t3k1ndo');
        $user->update();
    }
}
