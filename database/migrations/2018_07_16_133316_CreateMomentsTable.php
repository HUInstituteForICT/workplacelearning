<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMomentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('moments', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('rangeStart');
            $table->integer('rangeEnd');
            $table->unsignedInteger('tip_id');
        });

        Schema::table('moments', function (Blueprint $table): void {
            $table->foreign('tip_id', 'moment_to_tip')->references('id')->on('tips')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moments');
    }
}
