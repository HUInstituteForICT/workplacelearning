<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersettingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usersetting', function(Blueprint $table)
		{
			$table->integer('setting_id', true);
			$table->string('setting_label');
			$table->string('setting_value', 500)->nullable();
			$table->integer('student_id')->index('fk_UserSetting_Student1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('usersetting');
	}

}
