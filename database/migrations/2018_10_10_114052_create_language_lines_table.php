<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageLinesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('language_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group');
            $table->index('group');
            $table->string('key');
            $table->text('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('language_lines');
    }
}
