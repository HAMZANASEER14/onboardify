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
        Schema::create('invites', function (Blueprint $table) {
            $table->id();
             $table->foreignId('admin_id')->constrained('users');
            $table->string('email');
            $table->enum('status', ['pending', 'sent', 'failed', 'joined'])->default('pending');
            $table->text('failure_reason')->nullable();
            $table->string('source')->default('manual'); // manual | csv
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invites');
    }
};
