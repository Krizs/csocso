<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->integer('team_a_score')->nullable();
            $table->integer('team_b_score')->nullable();
            $table->dateTime('played_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->dropColumn('team_a_score');
            $table->dropColumn('team_b_score');
            $table->dropColumn('played_at');
        });
    }
};
