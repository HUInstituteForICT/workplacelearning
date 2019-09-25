<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEvidenceToLAA extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('learningactivityacting', function (Blueprint $table): void {
            $table->string('evidence_filename')->nullable();
            $table->string('evidence_disk_filename')->nullable();
            $table->string('evidence_mime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learningactivityacting', function (Blueprint $table): void {
            $table->dropColumn(['evidence_filename', 'evidence_disk_filename', 'evidence_mime']);
        });
    }
}
