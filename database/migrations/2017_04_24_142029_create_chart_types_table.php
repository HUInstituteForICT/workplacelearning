<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChartTypesTable extends Migration {

	public function up()
	{
		Schema::create('chart_types', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
		});

        collect(["pie", "bar", "line"])->each(function($type) {
            (new \App\ChartType(["name" => ucfirst($type), "slug" => $type]))->save();
        });

    }

	public function down()
	{
		Schema::drop('chart_types');
	}
}