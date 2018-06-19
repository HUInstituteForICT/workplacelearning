<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('');
            $table->integer('status')->default(0);
            $table->integer('wplp_id');
        });

        Schema::table('chains', function (Blueprint $table) {
            $table->foreign('wplp_id',
                'chain_to_wplp')->references('wplp_id')->on('workplacelearningperiod')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chains');
    }
}
