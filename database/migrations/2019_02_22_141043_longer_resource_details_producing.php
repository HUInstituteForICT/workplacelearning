<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LongerResourceDetailsProducing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('learningactivityproducing', function(Blueprint $table) {
            $table->text('res_material_detail')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('learningactivityproducing', function(Blueprint $table) {
            $table->string('res_material_detail', 150)->nullable()->change();
        });
    }
}
