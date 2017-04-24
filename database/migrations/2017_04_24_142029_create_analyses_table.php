<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnalysesTable extends Migration {

	public function up()
	{
		Schema::create('analyses', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->text('query');
			$table->integer('cache_duration');
		});
	}

	public function down()
	{
		Schema::drop('analyses');
	}
}