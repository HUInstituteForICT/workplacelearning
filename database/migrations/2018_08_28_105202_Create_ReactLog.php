<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReactLog extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('react_logs', function (Blueprint $table): void {
            $table->increments('id');
            $table->longText('log');
            $table->boolean('fixed')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('react_logs');
    }
}
