<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Departments
        $departments = [
            ['name' => 'Information Technology', 'code' => 'IT',  'description' => 'Technology department'],
            ['name' => 'Human Resources',        'code' => 'HR',  'description' => 'People department'],
            ['name' => 'Finance',                'code' => 'FIN', 'description' => 'Finance department'],
            ['name' => 'Operations',             'code' => 'OPS', 'description' => 'Operations department'],
            ['name' => 'Marketing',              'code' => 'MKT', 'description' => 'Marketing department'],
            ['name' => 'Administration',         'code' => 'ADM', 'description' => 'Administration department'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Admin
        User::create([
            'name'       => 'System Admin',
            'email'      => 'admin@company.com',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'department' => 'Administration',
            'is_active'  => true,
        ]);

        // Accountant
        User::create([
            'name'       => 'Jean Paul',
            'email'      => 'accountant@company.com',
            'password'   => Hash::make('password'),
            'role'       => 'accountant',
            'department' => 'Finance',
            'is_active'  => true,
        ]);

        // Employee
        User::create([
            'name'       => 'Ndikumana Jeanpierre',
            'email'      => 'employee@company.com',
            'password'   => Hash::make('password'),
            'role'       => 'employee',
            'department' => 'Information Technology',
            'is_active'  => true,
        ]);
    }
}