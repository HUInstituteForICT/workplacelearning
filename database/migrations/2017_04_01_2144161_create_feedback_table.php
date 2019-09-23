<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table): void {
            $table->integer('fb_id', true);
            $table->integer('learningactivity_id')->index('fk_Feedback_LearningActivityProducing1_idx');
            $table->string('notfinished', 100);
            $table->string('initiative', 500)->nullable();
            $table->integer('progress_satisfied');
            $table->integer('support_requested');
            $table->string('supported_provided_wp', 500)->nullable();
            $table->string('nextstep_self', 500)->nullable();
            $table->string('support_needed_wp', 500)->nullable();
            $table->string('support_needed_ed', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('feedback');
    }
}
