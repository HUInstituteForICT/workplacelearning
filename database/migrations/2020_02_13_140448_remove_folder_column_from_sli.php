<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFolderColumnFromSli extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('saved_learning_items', function (Blueprint $table) {
            $table->dropForeign('fk_saved_learning_items_folder');
            $table->dropColumn('folder');
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
            $table->integer('folder')->unsigned()->nullable(true);
        });
    }
}
