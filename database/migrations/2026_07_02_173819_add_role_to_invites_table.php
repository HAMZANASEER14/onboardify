<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invites', function (Blueprint $table) {
            // Add the role column (defaults to 'employee')
            $table->string('role')->default('employee')->after('email');
        });
    }

    public function down()
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};