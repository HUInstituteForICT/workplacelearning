<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignkeysToColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('column', function (Blueprint $table) {
            $table->foreign('gla_id', 'fk_column_GenericLearningActivity1')->references('gla_id')->on('genericlearningactivity')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('fieldtype_id', 'fk_column_Fieldtype1')->references('fieldtype_id')->on('fieldtype')->onUpdate('NO ACTION')->onDelete('NO ACTION');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('column', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_COLUMN_GENERICLEARNINGACTIVITY1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_COLUMN_FIELDTYPE1');
            }
        });
    }
}
