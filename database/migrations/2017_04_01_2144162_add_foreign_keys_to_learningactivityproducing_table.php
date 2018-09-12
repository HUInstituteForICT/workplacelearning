<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLearningactivityproducingTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('learningactivityproducing', function (Blueprint $table): void {
            $table->foreign('category_id', 'fk_LearningActivityProducing_Category1')->references('category_id')->on('category')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('difficulty_id', 'fk_LearningActivityProducing_Difficulty1')->references('difficulty_id')->on('difficulty')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('prev_lap_id', 'fk_LearningActivityProducing_LearningActivityProducing1')->references('lap_id')->on('learningactivityproducing')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('res_material_id', 'fk_LearningActivityProducing_ResourceMaterial1')->references('rm_id')->on('resourcematerial')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('res_person_id', 'fk_LearningActivityProducing_ResourcePerson1')->references('rp_id')->on('resourceperson')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('status_id', 'fk_LearningActivityProducing_Status1')->references('status_id')->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('wplp_id', 'fk_LearningActivityProducing_WorkplaceLearningPeriod1')->references('wplp_id')->on('workplacelearningperiod')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learningactivityproducing', function (Blueprint $table): void {
            $table->dropForeign('fk_LearningActivityProducing_Category1');
            $table->dropForeign('fk_LearningActivityProducing_Difficulty1');
            $table->dropForeign('fk_LearningActivityProducing_LearningActivityProducing1');
            $table->dropForeign('fk_LearningActivityProducing_ResourceMaterial1');
            $table->dropForeign('fk_LearningActivityProducing_ResourcePerson1');
            $table->dropForeign('fk_LearningActivityProducing_Status1');
            $table->dropForeign('fk_LearningActivityProducing_WorkplaceLearningPeriod1');
        });
    }
}
