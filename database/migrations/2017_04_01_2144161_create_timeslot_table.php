<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimeslotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timeslot', function(Blueprint $table)
		{
			$table->integer('timeslot_id', true);
			$table->string('timeslot_text', 45);
			$table->integer('edprog_id')->index('fk_Timeslot_EducationProgram1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('timeslot');
	}

}
