<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPermissionsGrant2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::select("GRANT SELECT ON learningactivityacting TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON learningactivityproducing TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (access_id) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (session_id) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (screen_width) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (screen_height) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (user_agent) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (OS) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (url) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (timestamp) ON accesslog TO 'wpldashboard'@'%';");
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
