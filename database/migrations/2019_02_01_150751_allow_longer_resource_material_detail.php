<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowLongerResourceMaterialDetail extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('learningactivityproducing', function (Blueprint $table) {
            $table->string('res_material_detail', 150)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
