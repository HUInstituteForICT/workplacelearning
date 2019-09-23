<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(SetupSeeder::class);
        $this->call(ChartTypesSeeder::class);
        $this->call(WplUserSeeder::class);
        if (class_exists(AccountSeeder::class)) {
            $this->call(AccountSeeder::class);
        }
    }
}
