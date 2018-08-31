<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAccesslogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('accesslog', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_AccessLog_Student1')->references('student_id')->on('student')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('accesslog', function (Blueprint $table) {
            $table->dropForeign('fk_AccessLog_Student1');
        });
    }
}
