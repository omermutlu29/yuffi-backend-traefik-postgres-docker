<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $path = __DIR__.'./../sql/cities.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('cities table seeded!');
        $path = 'database/sql/towns.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('towns table seeded!');
        $path = 'database/sql/child_years.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('child_years table seeded!');
        $path = 'database/sql/genders.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('genders table seeded!');
        $path = 'database/sql/point_types.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('point_types table seeded!');
        $path = 'database/sql/time_statuses.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('time_statuses table seeded!');
        $path = 'database/sql/appointment_locations.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('appointment_locations table seeded!');
        $path = 'database/sql/appointment_statuses.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('appointment_statuses table seeded!');

    }
}
