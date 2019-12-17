<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => '$2y$10$RigVH6ZOhh4V3xyTIGjj7uGo8umXbeNX/VcO8ZiH4rQBYIBIGD.JS',
                'remember_token' => null,
                'approved'       => 1,
                'workphone'      => '',
            ],
        ];

        User::insert($users);
    }
}
