<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionToTemplate extends Migration
{
    private $table = 'templates';
    private $column = 'description';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->table, function (Blueprint $table): void {
            $table->text($this->column)->nullable();
        });
        \DB::raw('UPDATE '.$this->table.' SET '.$this->column." = '' WHERE ".$this->column.' IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->table, function (Blueprint $table): void {
            $table->dropColumn($this->column);
        });
    }
}
