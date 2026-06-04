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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Onboarding control
    $table->string('business_type')->nullable(); // solo / business
    $table->string('onboarding_step')->default('profile_pending');

    // Personal info
    $table->string('first_name')->nullable();
    $table->string('last_name')->nullable();
    $table->string('phone')->nullable();

    // Business info (only used if business type)
    $table->string('company_name')->nullable();
    $table->string('industry')->nullable();
    $table->string('domain')->nullable();

    // Address info
    $table->string('location')->nullable(); // city / country
    $table->text('address')->nullable();    // full address

    // Extra
    $table->text('bio')->nullable();
    $table->string('profile_image')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
