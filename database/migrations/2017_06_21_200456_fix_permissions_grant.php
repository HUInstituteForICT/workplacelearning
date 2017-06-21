<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPermissionsGrant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::select("REVOKE SELECT ON `learningactivityproducing` FROM 'wpldashboard'@'%';");
        \DB::select("REVOKE SELECT ON `learningactivityacting` FROM 'wpldashboard'@'%';");
        \DB::select("REVOKE SELECT ON `accesslog` FROM 'wpldashboard'@'%';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
