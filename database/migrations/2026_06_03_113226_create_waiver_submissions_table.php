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
    Schema::create('waiver_submissions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('waiver_id')->constrained()->onDelete('cascade');
        $table->foreignId('sent_by')->constrained('users')->onDelete('cascade');
        $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');

        $table->string('token');

        $table->longText('responses')->nullable();
        $table->longText('signature')->nullable();

        $table->string('status')->default('signed');

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waiver_submissions');
    }
};
