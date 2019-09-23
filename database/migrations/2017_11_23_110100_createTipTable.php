<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipTable extends Migration
{
    public function up(): void
    {
        Schema::create('tips', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->string('tipText', 1000)->default('');
            $table->boolean('showInAnalysis')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('tips');
    }
}
