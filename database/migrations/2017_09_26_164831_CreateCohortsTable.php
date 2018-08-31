<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCohortsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cohorts', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('ep_id')->nullable();
            $table->boolean('disabled')->default(false);

            $table->foreign('ep_id', 'fk_Cohorts_EducationProgram')
                ->references('ep_id')
                ->on('educationprogram')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cohorts');
    }
}
