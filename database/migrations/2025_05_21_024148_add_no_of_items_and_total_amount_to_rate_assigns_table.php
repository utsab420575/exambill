<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rate_assigns', function (Blueprint $table) {
            $table->integer('no_of_items')->default(0)->after('session_id');
            $table->decimal('total_amount', 10, 2)->default(0)->after('no_of_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rate_assigns', function (Blueprint $table) {
            $table->dropColumn(['no_of_items', 'total_amount']);
        });
    }
};
