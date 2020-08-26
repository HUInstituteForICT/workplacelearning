<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folder', function (Blueprint $table) {
            $table->increments('folder_id');
            $table->timestamps();
            $table->string('title')->nullable(false);
            $table->string('description')->nullable(false);
            $table->integer('student_id')->nullable(false);
        });
        Schema::table('folder', function(Blueprint $table) {
            $table->foreign('student_id', 'optin_folder_to_student_id')
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
        Schema::dropIfExists('folder');
    }
}
