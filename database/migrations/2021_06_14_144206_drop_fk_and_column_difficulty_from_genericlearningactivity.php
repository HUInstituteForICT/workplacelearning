<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFkAndColumnDifficultyFromGenericlearningactivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('genericlearningactivity', function (Blueprint $table) {
            $table->dropForeign('FK_GENERICLEARNINGACTIVITY_DIFFICULTY1');
            $table->dropColumn(['difficulty_id']);
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
            $table->foreign('difficulty_id', 'fk_genericlearningactivity_Difficulty1')->references('difficulty_id')->on('difficulty')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->integer('difficulty_id')->index('fk_genericlearningactivity_Difficulty1_idx');
        });
    }
}
