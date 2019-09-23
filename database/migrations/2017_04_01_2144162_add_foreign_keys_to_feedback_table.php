<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table): void {
            $table->foreign('learningactivity_id', 'fk_Feedback_LearningActivityProducing1')->references('lap_id')->on('learningactivityproducing')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table): void {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_FEEDBACK_LEARNINGACTIVITYPRODUCING1');
            }
        });
    }
}
