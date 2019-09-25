<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChainIdToLAP extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('learningactivityproducing', function (Blueprint $table): void {
            $table->integer('chain_id')->unsigned()->nullable();
        });

        Schema::table('learningactivityproducing', function (Blueprint $table): void {
            $table->foreign('chain_id', 'chain_to_lap')->references('id')->on('chains')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learningactivityproducing', function (Blueprint $table): void {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('CHAIN_TO_LAP');
            }
            $table->dropColumn('chain_id');
        });
    }
}
