<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFkAndColumnStatusFromGenericlearningactivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('genericlearningactivity', function (Blueprint $table) {
            $table->dropForeign('FK_GENERICLEARNINGACTIVITY_STATUS1');
            $table->dropColumn(['status_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('genericlearningactivity', function (Blueprint $table) {
            $table->foreign('status_id', 'fk_genericlearningactivity_Status1')->references('status_id')->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->integer('status_id')->index('fk_genericlearningactivity_Status1_idx');
        });
    }
}
