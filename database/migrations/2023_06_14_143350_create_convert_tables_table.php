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
        Schema::create('convert_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('to_currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->float('convert_rate');
            $table->integer('request_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convert_tables');
    }
};
