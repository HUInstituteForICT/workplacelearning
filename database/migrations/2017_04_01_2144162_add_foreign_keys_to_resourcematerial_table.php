<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResourcematerialTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resourcematerial', function(Blueprint $table)
		{
			$table->foreign('wplp_id', 'fk_ResourceMaterial_WorkplaceLearningPeriod1')->references('wplp_id')->on('workplacelearningperiod')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resourcematerial', function(Blueprint $table)
		{
			$table->dropForeign('fk_ResourceMaterial_WorkplaceLearningPeriod1');
		});
	}

}
