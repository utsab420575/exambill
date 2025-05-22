<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rate_heads', function (Blueprint $table) {
            $table->string('order_no')->change();  // Change from int to varchar
        });
    }

    public function down()
    {
        Schema::table('rate_heads', function (Blueprint $table) {
            $table->integer('order_no')->default(0)->change();  // Revert back to int
        });
    }
};
