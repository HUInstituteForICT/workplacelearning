<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompetenceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('competence', function(Blueprint $table)
		{
			$table->integer('competence_id', true);
			$table->string('competence_label', 45);
			$table->integer('educationprogram_id')->index('fk_Competency_EducationProgram1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('competence');
	}

}
