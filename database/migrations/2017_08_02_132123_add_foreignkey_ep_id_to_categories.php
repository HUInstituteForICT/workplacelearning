<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyEpIdToCategories extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('category', function (Blueprint $table): void {
            $table->foreign('ep_id', 'fk_Category_EducationProgram')->references('ep_id')->on('educationprogram')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category', function (Blueprint $table): void {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_CATEGORY_EDUCATIONPROGRAM');
            }
        });
    }
}
