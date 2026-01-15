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
        ['email' => 'spvqc@gmail.com'],
        [
            'name' => 'Supervisor QC',
            'password' => Hash::make('spv123'),
            'role' => 'spv'
        ]
    );

    User::updateOrCreate(
        ['email' => 'staffqc@gmail.com'],
        [
            'name' => 'Staff QC',
            'password' => Hash::make('staff123'),
            'role' => 'staff'
        ]
    );

        User::updateOrCreate(
        ['email' => 'managerproduct@gmail.com'],
        [
            'name' => 'Manager Production',
            'password' => Hash::make('manager123'),
            'role' => 'manager'
        ]

        
    );

            User::updateOrCreate(
        ['email' => 'inuriadi73@gmail.com'],
        [
            'name' => 'Manager Production',
            'password' => Hash::make('iqmal123'),
            'role' => 'manager'
        ]

        
    );

    }
}
