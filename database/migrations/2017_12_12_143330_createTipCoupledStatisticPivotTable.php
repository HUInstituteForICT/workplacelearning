<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipCoupledStatisticPivotTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tip_coupled_statistic', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('tip_id')->unsigned();
            $table->integer('statistic_id')->unsigned();
            $table->smallInteger('comparison_operator')->unsigned();
            $table->float('threshold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('tip_coupled_statistic');
    }
}
