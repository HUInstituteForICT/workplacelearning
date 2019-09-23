<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

class VerifyCurrentAccounts extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::table('student')
            ->update(['email_verified_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
