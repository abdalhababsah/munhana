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
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'role' => 'admin',
                'phone' => '+971500000000',
                'language' => 'ar',
            ],
            [
                'name' => 'Client One',
                'email' => 'client1@test.com',
                'role' => 'client',
                'phone' => '+971500000001',
                'language' => 'en',
            ],
            [
                'name' => 'Client Two',
                'email' => 'client2@test.com',
                'role' => 'client',
                'phone' => '+971500000002',
                'language' => 'ar',
            ],
            [
                'name' => 'Client Three',
                'email' => 'client3@test.com',
                'role' => 'client',
                'phone' => '+971500000003',
                'language' => 'en',
            ],
            [
                'name' => 'Worker One',
                'email' => 'worker1@test.com',
                'role' => 'worker',
                'phone' => '+971500000010',
                'language' => 'ar',
            ],
            [
                'name' => 'Worker Two',
                'email' => 'worker2@test.com',
                'role' => 'worker',
                'phone' => '+971500000011',
                'language' => 'en',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'phone' => $user['phone'],
                    'language' => $user['language'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}

