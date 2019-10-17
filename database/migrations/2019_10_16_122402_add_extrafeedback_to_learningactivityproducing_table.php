<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtrafeedbackToLearningactivityproducingTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('learningactivityproducing', function (Blueprint $table) {
            $table->integer('extrafeedback')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('learningactivityproducing', function (Blueprint $table) {
        });
    }
}
