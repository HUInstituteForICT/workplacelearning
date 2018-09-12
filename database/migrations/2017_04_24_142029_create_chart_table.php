<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChartTable extends Migration
{
    public function up(): void
    {
        Schema::create('chart', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('analysis_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->string('label', 255);
        });
    }

    public function down(): void
    {
        Schema::drop('chart');
    }
}
