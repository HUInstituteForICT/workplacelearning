<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->integer('category_id', true);
            $table->string('category_label', 45)->nullable();
            $table->integer('wplp_id')->index('fk_Category_WorkplaceLearningPeriod1_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('category');
    }
}
