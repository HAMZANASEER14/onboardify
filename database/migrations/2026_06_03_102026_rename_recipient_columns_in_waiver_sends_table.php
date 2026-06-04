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
             $table->renameColumn('recipient_name',  'client_name');
        $table->renameColumn('recipient_email', 'client_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waiver_sends', function (Blueprint $table) {
            //
        });
    }
};
