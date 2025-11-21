<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {

    User::updateOrCreate(
        ['email' => 'spvqc@sehatiapp.com'],
        [
            'name' => 'Supervisor QC',
            'password' => Hash::make('spv123'),
            'role' => 'spv'
        ]
    );

    User::updateOrCreate(
        ['email' => 'staffqc@sehatiapp.com'],
        [
            'name' => 'Staff QC',
            'password' => Hash::make('staff123'),
            'role' => 'staff'
        ]
    );

    }
}
