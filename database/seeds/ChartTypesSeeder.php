<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class ChartTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(['pie', 'bar', 'line'])->each(function ($type): void {
            (new \App\ChartType(['name' => ucfirst($type), 'slug' => $type]))->save();
        });
    }
}
