<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFolderCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folder_comments', function (Blueprint $table) {
            $table->increments('folder_comments_id');
            $table->timestamps();
            $table->string('text')->nullable(false);
            $table->integer('folder_id')->unsigned()->nullable(false);
        });
        Schema::table('folder_comments', function(Blueprint $table) {
            $table->foreign('folder_id', 'optin_to_folder_id')
                ->references('folder_id')->on('folder')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('folder_comments');
    }
}
