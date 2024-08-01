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
        Schema::create('head_office_sales', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('sets');
            $table->decimal('per_set_price', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->decimal('cash', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('head_office_sales');
    }
};
