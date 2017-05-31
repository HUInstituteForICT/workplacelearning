<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLearninggoalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('learninggoal', function(Blueprint $table)
		{
			$table->foreign('wplp_id', 'fk_LearningGoal_WorkplaceLearningPeriod1')->references('wplp_id')->on('workplacelearningperiod')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('learninggoal', function(Blueprint $table)
		{
			$table->dropForeign('fk_LearningGoal_WorkplaceLearningPeriod1');
		});
	}

}
