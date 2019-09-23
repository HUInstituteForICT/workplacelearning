<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLearninggoalTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('learninggoal', function (Blueprint $table): void {
            $table->foreign('wplp_id', 'fk_LearningGoal_WorkplaceLearningPeriod1')->references('wplp_id')->on('workplacelearningperiod')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learninggoal', function (Blueprint $table): void {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_LEARNINGGOAL_WORKPLACELEARNINGPERIOD1');
            }
        });
    }
}
