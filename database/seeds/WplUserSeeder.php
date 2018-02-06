<?php

use Illuminate\Database\Seeder;

class WplUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::select("CREATE USER IF NOT EXISTS 'wpldashboard'@'%' IDENTIFIED BY 'WPLdashboard';");

        \DB::select("GRANT SELECT ON learningactivityproducing TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON analyses TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON category TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON chart TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON competence TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON difficulty TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON educationprogram TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON educationprogramtype TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON learningactivityacting TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON learningactivityproducing TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON learninggoal TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON resourcematerial TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT ON status TO 'wpldashboard'@'%';");

        \DB::select("GRANT SELECT (access_id) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (session_id) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (screen_width) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (screen_height) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (user_agent) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (OS) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (url) ON accesslog TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (timestamp) ON accesslog TO 'wpldashboard'@'%';");

        \DB::select("GRANT SELECT (dl_id) ON deadline TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (dl_value) ON deadline TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (dl_datetime) ON deadline TO 'wpldashboard'@'%';");

        \DB::select("GRANT SELECT (setting_id) ON usersetting TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (setting_label) ON usersetting TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (setting_value) ON usersetting TO 'wpldashboard'@'%';");

        \DB::select("GRANT SELECT (wp_id) ON workplace TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (wp_name) ON workplace TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (street) ON workplace TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (housenr) ON workplace TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (postalcode) ON workplace TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (numberofemployees) ON workplace TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (town) ON workplace TO 'wpldashboard'@'%';");

        \DB::select("GRANT SELECT (wplp_id) ON workplacelearningperiod TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (wp_id) ON workplacelearningperiod TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (startdate) ON workplacelearningperiod TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (enddate) ON workplacelearningperiod TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (nrofdays) ON workplacelearningperiod TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (description) ON workplacelearningperiod TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (is_in_analytics) ON workplacelearningperiod TO 'wpldashboard'@'%';");
        \DB::select("GRANT SELECT (cohort_id) ON workplacelearningperiod TO 'wpldashboard'@'%';");
    }
}
