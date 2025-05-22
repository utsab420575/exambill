<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('rate_assigns', function (Blueprint $table) {
            $table->decimal('no_of_items', 10, 2)->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('rate_assigns', function (Blueprint $table) {
            $table->integer('no_of_items')->default(0)->change();
        });
    }
};
