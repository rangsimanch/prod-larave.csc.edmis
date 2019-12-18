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
                'password'       => '$2y$10$X8qsGdvgXU6z86LuX0dUyee0OsamlTtSd3QF9UkdBkFb4ocjN3r8S',
                'remember_token' => null,
                'approved'       => 1,
                'workphone'      => '',
            ],
        ];

        User::insert($users);
    }
}
