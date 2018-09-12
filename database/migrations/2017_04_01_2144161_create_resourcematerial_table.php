<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcematerialTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resourcematerial', function (Blueprint $table): void {
            $table->integer('rm_id', true);
            $table->string('rm_label', 45);
            $table->integer('wplp_id')->index('fk_ResourceMaterial_WorkplaceLearningPeriod1_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('resourcematerial');
    }
}
