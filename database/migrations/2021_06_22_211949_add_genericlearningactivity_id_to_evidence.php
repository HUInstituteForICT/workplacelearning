<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGenericlearningactivityIdToEvidence extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evidence', function (Blueprint $table) {
            $table->integer('genericlearningactivity_id')->index('fk_Evidence_GenericLearningActivity1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evidence', function (Blueprint $table) {
            $table->dropColumn('genericlearningactivity_id');
        });
    }
}
