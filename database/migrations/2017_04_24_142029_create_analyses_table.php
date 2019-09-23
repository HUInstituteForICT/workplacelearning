<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnalysesTable extends Migration
{
    public function up(): void
    {
        Schema::create('analyses', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->text('query');
            $table->integer('cache_duration');
        });
    }

    public function down(): void
    {
        Schema::drop('analyses');
    }
}
