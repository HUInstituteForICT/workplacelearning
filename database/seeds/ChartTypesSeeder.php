<?php

use Illuminate\Database\Seeder;

class ChartTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        collect(['pie', 'bar', 'line'])->each(function ($type) {
            (new \App\ChartType(['name' => ucfirst($type), 'slug' => $type]))->save();
        });
    }
}
