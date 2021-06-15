<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFksFromLearningactivityproducing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('learningactivityproducing', function (Blueprint $table) {
            $table->dropForeign('FK_LEARNINGACTIVITYPRODUCING_CATEGORY1');
            $table->dropForeign('FK_LEARNINGACTIVITYPRODUCING_DIFFICULTY1');
            $table->dropForeign('FK_LEARNINGACTIVITYPRODUCING_LEARNINGACTIVITYPRODUCING1');
            $table->dropForeign('FK_LEARNINGACTIVITYPRODUCING_RESOURCEMATERIAL1');
            $table->dropForeign('FK_LEARNINGACTIVITYPRODUCING_RESOURCEPERSON1');
            $table->dropForeign('FK_LEARNINGACTIVITYPRODUCING_STATUS1');
            $table->dropForeign('FK_LEARNINGACTIVITYPRODUCING_WORKPLACELEARNINGPERIOD1');
            $table->dropForeign('CHAIN_TO_LAP');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('learningactivityproducing', function (Blueprint $table) {
            $table->foreign('category_id', 'fk_LearningActivityProducing_Category1')->references('category_id')->on('category')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('difficulty_id', 'fk_LearningActivityProducing_Difficulty1')->references('difficulty_id')->on('difficulty')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('prev_lap_id', 'fk_LearningActivityProducing_LearningActivityProducing1')->references('lap_id')->on('learningactivityproducing')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('res_material_id', 'fk_LearningActivityProducing_ResourceMaterial1')->references('rm_id')->on('resourcematerial')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('res_person_id', 'fk_LearningActivityProducing_ResourcePerson1')->references('rp_id')->on('resourceperson')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('status_id', 'fk_LearningActivityProducing_Status1')->references('status_id')->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('wplp_id', 'fk_LearningActivityProducing_WorkplaceLearningPeriod1')->references('wplp_id')->on('workplacelearningperiod')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('chain_id', 'chain_to_lap')->references('id')->on('chains')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }
}

