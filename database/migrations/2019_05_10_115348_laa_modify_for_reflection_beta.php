<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaaModifyForReflectionBeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('learningactivityacting', function (Blueprint $table) {
            $table->boolean('is_from_reflection_beta')->default(false);
            $table->string('support_wp', 500)->nullable()->change();
            $table->string('support_ed', 500)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('learningactivityacting', function (Blueprint $table) {
            $table->string('support_wp', 500)->change();
            $table->string('support_ed', 500)->change();
            $table->removeColumn('is_from_reflection_beta');
        });
    }
}
