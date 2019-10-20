<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeedbackIncreaseTextFieldsCharLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedback', function (Blueprint $table): void {
            $table->string('supported_provided_wp', 1000)->change();
            $table->string('initiative', 1000)->change();
            $table->string('nextstep_self', 1000)->change();
            $table->string('support_needed_wp', 1000)->change();
            $table->string('support_needed_ed', 1000)->change();
            $table->string('notfinished', 1000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedback', function (Blueprint $table): void {
            $table->string('supported_provided_wp', 500)->change();
            $table->string('initiative', 500)->change();
            $table->string('nextstep_self', 500)->change();
            $table->string('support_needed_wp', 500)->change();
            $table->string('support_needed_ed', 500)->change();
            $table->string('notfinished', 100)->change();
        });

    }
}
