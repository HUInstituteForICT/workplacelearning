<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyEpIdToCategories extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->foreign('ep_id', 'fk_Category_EducationProgram')->references('ep_id')->on('educationprogram')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->dropForeign('fk_Category_EducationProgram');
        });
    }
}
