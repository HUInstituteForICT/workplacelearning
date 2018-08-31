<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditLAPDescriptionLength extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('learningactivityproducing', function (Blueprint $table) {
            $table->string('description', 1000)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('learningactivityproducing', function (Blueprint $table) {
            $table->string('description', 100)->change();
        });
    }
}
