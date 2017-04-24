<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('labels', function(Blueprint $table) {
			$table->foreign('chart_id')->references('label')->on('chart')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('chart', function(Blueprint $table) {
			$table->foreign('analysis_id')->references('id')->on('analyses')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('chart', function(Blueprint $table) {
			$table->foreign('type_id')->references('id')->on('chart_types')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('labels', function(Blueprint $table) {
			$table->dropForeign('labels_chart_id_foreign');
		});
		Schema::table('chart', function(Blueprint $table) {
			$table->dropForeign('chart_analysis_id_foreign');
		});
		Schema::table('chart', function(Blueprint $table) {
			$table->dropForeign('chart_type_id_foreign');
		});
	}
}