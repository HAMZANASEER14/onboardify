<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('waiver_sends', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->change();
            $table->string('recipient_name')->nullable()->after('client_id');
            $table->string('recipient_email')->nullable()->after('recipient_name');
        });
    }

    public function down(): void
    {
        Schema::table('waiver_sends', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable(false)->change();
            $table->dropColumn(['recipient_name', 'recipient_email']);
        });
    }
};
