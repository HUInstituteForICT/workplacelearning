<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPasswordResetTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('password_reset', function (Blueprint $table) {
            $table->foreign('email', 'pw_reset_email')->references('email')->on('student')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('password_reset', function (Blueprint $table) {
            $table->dropForeign('pw_reset_email');
        });
    }
}
