<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcepersonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resourceperson', function(Blueprint $table)
		{
			$table->integer('rp_id', true);
			$table->string('person_label', 45);
			$table->integer('ep_id')->index('fk_ResourcePerson_EducationProgram1_idx');
			$table->integer('wplp_id')->index('fk_ResourcePerson_WorkplaceLearningPeriod1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resourceperson');
	}

}
