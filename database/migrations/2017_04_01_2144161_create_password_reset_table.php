<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePasswordResetTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('password_reset', function (Blueprint $table) {
            $table->string('email')->index('pw_reset_email_idx');
            $table->string('token', 64);
            $table->dateTime('created_at');
            $table->index(['email', 'token'], 'email_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('password_reset');
    }
}
