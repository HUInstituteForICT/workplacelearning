<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLabelsTable extends Migration {

	public function up()
	{
		Schema::create('labels', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('chart_id')->unsigned();
			$table->string('name', 65);
		});
	}

	public function down()
	{
		Schema::drop('labels');
	}
}