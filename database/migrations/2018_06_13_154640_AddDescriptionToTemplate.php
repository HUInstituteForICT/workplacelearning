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
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->table, function(Blueprint $table) {
            $table->text($this->column)->nullable();
        });
        \DB::raw("UPDATE " . $this->table . " SET " . $this->column . " = '' WHERE " . $this->column . " IS NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->table, function(Blueprint $table) {
            $table->dropColumn($this->column);
        });
    }
}
