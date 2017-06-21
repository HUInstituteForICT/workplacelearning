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
        \DB::select("REVOKE SELECT ON `learningactivityproducing` TO 'wpldashboard'@'%';");
        \DB::select("REVOKE SELECT ON `learningactivityacting` TO 'wpldashboard'@'%';");
        \DB::select("REVOKE SELECT ON `accesslog` TO 'wpldashboard'@'%';");
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
