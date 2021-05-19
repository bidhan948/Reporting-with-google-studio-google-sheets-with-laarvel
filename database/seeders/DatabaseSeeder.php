<?php

namespace Database\Seeders;

use App\Models\data_entry;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        return $this->call(data_entrySeeder::class);
    }
}
