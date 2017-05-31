<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFeedbackTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('feedback', function(Blueprint $table)
		{
			$table->foreign('learningactivity_id', 'fk_Feedback_LearningActivityProducing1')->references('lap_id')->on('learningactivityproducing')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('feedback', function(Blueprint $table)
		{
			$table->dropForeign('fk_Feedback_LearningActivityProducing1');
		});
	}

}
