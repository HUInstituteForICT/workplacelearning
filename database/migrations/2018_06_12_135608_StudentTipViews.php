<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StudentTipViews extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_tip_views', function (Blueprint $table): void {
            $table->integer('student_id');
            $table->unsignedInteger('tip_id');

            $table->primary(['student_id', 'tip_id']);
        });

        Schema::table('student_tip_views', function (Blueprint $table): void {
            $table->foreign('student_id',
                'student_tip_views_to_student')->references('student_id')->on('student')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('tip_id',
                'student_tip_views_to_tip')->references('id')->on('tips')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_tip_views', function (Blueprint $table): void {
            try {
                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign('STUDENT_TIP_VIEWS_TO_STUDENT');
                }
                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign('STUDENT_TIP_VIEWS_TO_TIP');
                }
            } catch (\Exception $exception) {
                // do nothing, foreign doesnt exist
            }
        });
        Schema::dropIfExists('student_tip_views');
    }
}
