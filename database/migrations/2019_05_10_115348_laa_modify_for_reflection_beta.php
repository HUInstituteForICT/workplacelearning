<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaaModifyForReflectionBeta extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('learningactivityacting', function (Blueprint $table) {
            $table->boolean('is_from_reflection_beta')->default(false);
            $table->string('support_wp', 500)->nullable()->change();
            $table->string('support_ed', 500)->nullable()->change();
            $table->string('lessonslearned', 1000)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('learningactivityacting', function (Blueprint $table) {
            $table->string('support_wp', 500)->nullable(false)->change();
            $table->string('support_ed', 500)->nullable(false)->change();
            $table->string('lessonslearned')->nullable(false)->change();
            $table->dropColumn('is_from_reflection_beta');
        });
    }
}
