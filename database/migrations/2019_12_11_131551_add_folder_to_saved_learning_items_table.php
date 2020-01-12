<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFolderToSavedLearningItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saved_learning_items', function (Blueprint $table) {
            $table->integer('folder')->unsigned()->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saved_learning_items', function (Blueprint $table) {
            $table->dropColumn('folder');
        });
    }
}
