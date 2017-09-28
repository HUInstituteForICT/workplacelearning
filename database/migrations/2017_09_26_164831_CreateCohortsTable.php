<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCohortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cohorts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('ep_id')->nullable();
            $table->integer('wplp_id')->nullable();

            $table->foreign('ep_id', 'fk_Cohorts_EducationProgram')
                ->references('ep_id')
                ->on('educationprogram')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION');

            $table->foreign('wplp_id', 'fk_Cohorts_Wplp')
                ->references('wplp_id')
                ->on('workplacelearningperiod')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cohorts');
    }
}
