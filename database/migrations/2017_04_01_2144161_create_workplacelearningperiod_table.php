<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkplacelearningperiodTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('workplacelearningperiod', function(Blueprint $table)
		{
			$table->integer('wplp_id', true);
			$table->integer('student_id')->index('fk_WorkplaceLearningPeriod_Student1_idx');
			$table->integer('wp_id')->index('fk_WorkplaceLearningPeriod_Workplace1_idx');
			$table->date('startdate');
			$table->date('enddate');
			$table->integer('nrofdays');
			$table->string('description', 500);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('workplacelearningperiod');
	}

}
