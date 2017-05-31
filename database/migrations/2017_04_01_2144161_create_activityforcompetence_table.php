<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivityforcompetenceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activityforcompetence', function(Blueprint $table)
		{
			$table->integer('afc_id', true);
			$table->integer('competence_id')->index('fk_ActivityForCompetency_Competency1');
			$table->integer('learningactivity_id')->index('fk_ActivityForCompetency_LearningActivityActing1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('activityforcompetence');
	}

}
