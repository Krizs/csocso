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
        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedBigInteger('goalkeeper_id')->nullable();
            $table->unsignedBigInteger('attacker_id')->nullable();

            $table->foreign('goalkeeper_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('attacker_id')->references('id')->on('players')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['goalkeeper_id']);
            $table->dropForeign(['attacker_id']);
            $table->dropColumn(['goalkeeper_id', 'attacker_id']);
        });
    }
};
