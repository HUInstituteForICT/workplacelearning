<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveEvidenceFieldsFromLAA extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('learningactivityacting', function (Blueprint $table) {
            $table->dropColumn(['evidence_filename', 'evidence_disk_filename', 'evidence_mime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('learningactivityacting', function (Blueprint $table) {
            $table->string('evidence_filename')->nullable();
            $table->string('evidence_disk_filename')->nullable();
            $table->string('evidence_mime')->nullable();
        });
    }
}
