<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LAASituationCharacterLength extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('learningactivityacting', function (Blueprint $table) {
            $table->string('situation', 1500)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
