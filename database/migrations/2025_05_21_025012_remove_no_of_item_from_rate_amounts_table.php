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
        Schema::table('rate_amounts', function (Blueprint $table) {
            $table->dropColumn('no_item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rate_amounts', function (Blueprint $table) {
            $table->integer('no_item')->nullable(); // or match the original type
        });
    }
};
