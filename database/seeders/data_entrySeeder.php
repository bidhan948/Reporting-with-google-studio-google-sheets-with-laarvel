<?php

namespace Database\Seeders;

use App\Models\data_entry;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class data_entrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * 
     */
    public function run()
    {
        $username = ['Bidhan', 'Togo', 'Coco', 'Cooper', 'Tom', 'Leo', 'Cruise', 'bruce', 'Krishala','Ethan','Jhon','Bradly'];
        $course_name = ['Bsc.Csit','Bsc.horns','Bit','BCA','Be.Computer','BHM','Bsc.Math','Bsc.Physics','BBS','BBA'];
        $faker = Faker::create();
        foreach (range(1,1000) as $index) {
            data_entry::create([
                'username' => $faker->randomElement($username),
                'course_name' => $faker->randomElement($course_name),
                'time' => rand(1, 8),
            ]);
        }
    }
}
