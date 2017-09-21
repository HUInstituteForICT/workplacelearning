<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(SetupSeeder::class);
        $this->call(seed_charttypes::class);
        if(class_exists(AccountSeeder::class)) {
            $this->call(AccountSeeder::class);
        }
    }
}
