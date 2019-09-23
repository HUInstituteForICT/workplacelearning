<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersettingTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usersetting', function (Blueprint $table): void {
            $table->integer('setting_id', true);
            $table->string('setting_label');
            $table->string('setting_value', 500)->nullable();
            $table->integer('student_id')->index('fk_UserSetting_Student1_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('usersetting');
    }
}
