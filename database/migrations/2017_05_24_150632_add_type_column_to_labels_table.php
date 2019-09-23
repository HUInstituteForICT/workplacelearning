<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeColumnToLabelsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('labels', function (Blueprint $table): void {
            $table->string('type')->nullable();
        });

        collect(['pie', 'bar', 'line'])->each(function ($type): void {
            (new \App\ChartType(['name' => ucfirst($type), 'slug' => $type]))->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labels', function (Blueprint $table): void {
            $table->dropColumn('type');
        });
    }
}
