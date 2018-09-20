<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToWorkplacelearningperiodTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workplacelearningperiod', function (Blueprint $table): void {
            $table->foreign('student_id', 'fk_WorkplaceLearningPeriod_Student1')->references('student_id')->on('student')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('wp_id', 'fk_WorkplaceLearningPeriod_Workplace1')->references('wp_id')->on('workplace')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workplacelearningperiod', function (Blueprint $table): void {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_WORKPLACELEARNINGPERIOD_STUDENT1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_WORKPLACELEARNINGPERIOD_WORKPLACE1');
            }
        });
    }
}
