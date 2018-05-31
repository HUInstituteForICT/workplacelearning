<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChainIdToLAP extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('learningactivityproducing', function(Blueprint $table) {
            $table->integer('chain_id')->nullable();
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
            $table->dropColumn('chain_id');
        });

    }
}
