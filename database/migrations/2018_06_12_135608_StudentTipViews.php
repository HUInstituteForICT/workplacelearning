<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StudentTipViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_tip_views', function (Blueprint $table) {
            $table->integer('student_id');
            $table->unsignedInteger('tip_id');

            $table->primary(['student_id', 'tip_id']);
        });

        Schema::table('student_tip_views', function (Blueprint $table) {
            $table->foreign('student_id',
                'student_tip_views_to_student')->references('student_id')->on('student')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('tip_id',
                'student_tip_views_to_tip')->references('id')->on('tips')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_tip_views', function (Blueprint $table) {
            try {
                $table->dropForeign('student_tip_views_to_student');
                $table->dropForeign('student_tip_views_to_tip');
            } catch(\Exception $exception) {
                // do nothing, foreign doesnt exist
            }
        });
        Schema::dropIfExists('student_tip_views');
    }
}
