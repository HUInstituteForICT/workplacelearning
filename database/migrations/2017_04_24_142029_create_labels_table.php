<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLabelsTable extends Migration
{
    public function up(): void
    {
        Schema::create('labels', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('chart_id')->unsigned();
            $table->string('name', 65);
        });
    }

    public function down(): void
    {
        Schema::drop('labels');
    }
}
