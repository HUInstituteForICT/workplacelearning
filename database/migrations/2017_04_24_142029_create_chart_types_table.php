<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChartTypesTable extends Migration
{
    public function up(): void
    {
        Schema::create('chart_types', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name', 255);
        });
    }

    public function down(): void
    {
        Schema::drop('chart_types');
    }
}
