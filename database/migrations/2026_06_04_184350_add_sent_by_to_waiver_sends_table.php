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
    Schema::table('waiver_sends', function (Blueprint $table) {
        $table->unsignedBigInteger('sent_by')->after('waiver_id')->nullable();
        $table->foreign('sent_by')->references('id')->on('users')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('waiver_sends', function (Blueprint $table) {
        $table->dropForeign(['sent_by']);
        $table->dropColumn('sent_by');
    });
}
};
