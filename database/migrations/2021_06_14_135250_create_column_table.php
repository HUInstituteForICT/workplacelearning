
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('column', function (Blueprint $table) {
            $table->integer('column_id', true);
            $table->integer('gla_id')->index('fk_column_GenericLearningActivity1_idx');
            $table->integer('fieldtype_id')->index('fk_column_Fieldtype1_idx');
            $table->string('name', 250);
            $table->string('column_options', 500);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('column');
    }
}
