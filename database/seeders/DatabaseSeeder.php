<?php

namespace Database\Seeders;

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
        $this->call([
            RoleSeeder::class,
            TaskStatusesSeeder::class,
            TaskSeeder::class,
            TaskNoteSeeder::class,
            UserSeeder::class,
        ]);
    }
}
