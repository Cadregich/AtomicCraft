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
        Schema::table('privileges', function (Blueprint $table) {
            $table->json('capabilities')->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('privileges', function (Blueprint $table) {
            $table->dropColumn('capabilities');
        });
    }
};
