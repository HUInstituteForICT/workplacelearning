<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvidenceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('evidence', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('learning_activity_acting_id');

            $table->string('filename');
            $table->string('disk_filename');
            $table->string('mime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('evidence');
    }
}
