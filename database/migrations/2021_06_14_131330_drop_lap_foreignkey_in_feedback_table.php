<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLapForeignkeyInFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropForeign('FK_FEEDBACK_LEARNINGACTIVITYPRODUCING1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('feedback', function (Blueprint $table): void {
            $table->foreign('learningactivity_id', 'fk_Feedback_LearningActivityProducing1')->references('lap_id')->on('learningactivityproducing')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }
}
