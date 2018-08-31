<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEducationprogramtypeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('educationprogramtype', function (Blueprint $table): void {
            $table->integer('eptype_id', true);
            $table->string('eptype_name', 45);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('educationprogramtype');
    }
}
