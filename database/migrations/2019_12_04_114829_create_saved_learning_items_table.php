<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavedLearningItemsTable extends Migration
{
    public function up()
    {
        Schema::create('saved_learning_items', function (Blueprint $table) {
            $table->increments('sli_id');
            $table->string('category')->nullable(false);
            $table->integer('item_id')->nullable(false);
            $table->integer('student_id')->nullable(false);
        });
        Schema::table('saved_learning_items', function(Blueprint $table) {
            $table->foreign('student_id', 'optin_to_student_id')
                ->references('student_id')->on('student')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saved_learning_items');
    }
}
