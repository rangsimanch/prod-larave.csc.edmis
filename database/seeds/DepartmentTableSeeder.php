<?php

use Illuminate\Database\Seeder;
use App\Department;
class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $department = [
            [
                'id'             => 1,
                'name'           => 'Administration Section',
                'code'          => 'ADS',
            ],
            
            [
                'id'             => 2,
                'name'           => 'Analysing and Testing Section',
                'code'          => 'ATS',
            ],

            [
                'id'             => 3,
                'name'           => 'Construction Section',
                'code'          => 'CS',
            ],

            [
                'id'             => 4,
                'name'           => 'Construction Supervision Section',
                'code'          => 'CSS',
            ],

            [
                'id'             => 5,
                'name'           => 'Management',
                'code'          => 'MGT',
            ],

            [
                'id'             => 6,
                'name'           => 'Safety and Quality Section',
                'code'          => 'SQS',
            ],

            [
                'id'             => 7,
                'name'           => 'Survey Section',
                'code'          => 'SS',
            ],


        ];

        Department::insert($department);
    }
}
