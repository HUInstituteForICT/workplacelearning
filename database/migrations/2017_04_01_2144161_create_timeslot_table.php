<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimeslotTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('timeslot', function (Blueprint $table): void {
            $table->integer('timeslot_id', true);
            $table->string('timeslot_text', 45);
            $table->integer('edprog_id')->index('fk_Timeslot_EducationProgram1_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('timeslot');
    }
}
