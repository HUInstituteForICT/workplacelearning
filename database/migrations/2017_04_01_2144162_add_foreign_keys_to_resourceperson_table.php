<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResourcepersonTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('resourceperson', function (Blueprint $table): void {
            $table->foreign('ep_id', 'fk_ResourcePerson_EducationProgram1')->references('ep_id')->on('educationprogram')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('wplp_id', 'fk_ResourcePerson_WorkplaceLearningPeriod1')->references('wplp_id')->on('workplacelearningperiod')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resourceperson', function (Blueprint $table): void {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_RESOURCEPERSON_EDUCATIONPROGRAM1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_RESOURCEPERSON_WORKPLACELEARNINGPERIOD1');
            }
        });
    }
}
