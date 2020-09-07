<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableFolderAndFolderCommentsAdjustInputTo1000Chars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('folder', function (Blueprint $table) {
            $table->string('description', 1000)->nullable()->change();
        });
        Schema::table('folder_comments', function (Blueprint $table) {
            $table->string('text', 1000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('folder', function (Blueprint $table) {
            $table->string('description', 255)->nullable()->change();
        });
        Schema::table('folder_comments', function (Blueprint $table) {
            $table->string('text', 255)->change();
        });
    }
   
}