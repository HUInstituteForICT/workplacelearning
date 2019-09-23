<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailVerificationToStudent extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('student', function (Blueprint $table) {
            $table->dateTime('email_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('student', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });
    }
}
