<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFilterAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::select("CREATE USER 'wpldashboard'@'%' IDENTIFIED BY 'WPLdashboard';");
        \DB::select("GRANT SELECT ON `accesslog` TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON `learningactivityacting` TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON `learningactivityproducing` TO 'wpldashboard'@'%';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::select("REVOKE SELECT ON `learningactivityproducing` TO 'wpldashboard'@'%';");
        \DB::select("REVOKE SELECT ON `learningactivityacting` TO 'wpldashboard'@'%';");
        \DB::select("REVOKE SELECT ON `accesslog` TO 'wpldashboard'@'%';");
        \DB::select("DROP USER 'wpldashboard'@'%';");
    }
}
