<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UrlHashSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\UrlHash::factory(200)->create();
    }
}
