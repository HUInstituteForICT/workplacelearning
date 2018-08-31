<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEvidenceToLAA extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('learningactivityacting', function (Blueprint $table) {
            $table->string('evidence_filename')->nullable();
            $table->string('evidence_disk_filename')->nullable();
            $table->string('evidence_mime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('learningactivityacting', function (Blueprint $table) {
            $table->dropColumn(['evidence_filename', 'evidence_disk_filename', 'evidence_mime']);
        });
    }
}
