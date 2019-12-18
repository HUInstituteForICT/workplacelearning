<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorToFolderCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('folder_comments', function (Blueprint $table) {
            $table->integer('author_id')->nullable(false);
        });

        Schema::table('folder_comments', function (Blueprint $table) {
            $table->foreign('author_id', 'optin_to_author_id')
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
        Schema::table('folder_comments', function (Blueprint $table) {
            //
        });
    }
}
