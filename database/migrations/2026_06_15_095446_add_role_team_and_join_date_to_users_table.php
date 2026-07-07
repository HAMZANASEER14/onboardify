<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // We removed the 'role' column because it already exists!

            // 1. Team Link (nullable because the very first admin won't have a team until they create one)
            $table->unsignedBigInteger('team_id')->nullable()->after('email');
            $table->foreign('team_id')->references('id')->on('teams')->nullOnDelete();
            
            // 2. Join Date (to calculate employment duration)
            $table->date('joined_at')->nullable()->after('team_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            // We only drop the columns this specific migration added
            $table->dropColumn(['team_id', 'joined_at']); 
        });
    }
};