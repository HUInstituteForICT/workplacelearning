<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEducationprogramTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('educationprogram', function(Blueprint $table)
		{
			$table->foreign('eptype_id', 'fk_EducationProgram_EducationProgramType1')->references('eptype_id')->on('educationprogramtype')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('educationprogram', function(Blueprint $table)
		{
			$table->dropForeign('fk_EducationProgram_EducationProgramType1');
		});
	}

}
