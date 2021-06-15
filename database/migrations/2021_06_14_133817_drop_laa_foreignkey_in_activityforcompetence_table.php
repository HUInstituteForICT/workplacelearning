<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLaaForeignkeyInActivityforcompetenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activityforcompetence', function (Blueprint $table) {
            $table->dropForeign('FK_ACTIVITYFORCOMPETENCY_LEARNINGACTIVITYACTING1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activityforcompetence', function (Blueprint $table) {
            $table->foreign('learningactivity_id', 'fk_ActivityForCompetency_LearningActivityActing1')->references('laa_id')->on('learningactivityacting')->onUpdate('NO ACTION')->onDelete('NO ACTION');


        });
    }
}
