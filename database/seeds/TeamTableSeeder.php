<?php

use Illuminate\Database\Seeder;
use App\Team;

class TeamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = [
            [
                'id'             => 1,
                'name'           => 'State Railway of Thailand',
                'code'          => 'SRT',
            ],
            
            [
                'id'             => 2,
                'name'           => 'Project Management Consultant',
                'code'          => 'PMC',
            ],

            [
                'id'             => 3,
                'name'           => 'Construction Supervision Consultant',
                'code'          => 'CSC',
            ],

            [
                'id'             => 4,
                'name'           => 'Civil Engineering Company',
                'code'          => 'CEC',
            ],

        ];

        Team::insert($teams);
    }
}
