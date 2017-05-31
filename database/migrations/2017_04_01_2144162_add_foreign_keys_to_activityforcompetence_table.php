<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToActivityforcompetenceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('activityforcompetence', function(Blueprint $table)
		{
			$table->foreign('competence_id', 'fk_ActivityForCompetency_Competency1')->references('competence_id')->on('competence')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('learningactivity_id', 'fk_ActivityForCompetency_LearningActivityActing1')->references('laa_id')->on('learningactivityacting')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('activityforcompetence', function(Blueprint $table)
		{
			$table->dropForeign('fk_ActivityForCompetency_Competency1');
			$table->dropForeign('fk_ActivityForCompetency_LearningActivityActing1');
		});
	}

}
