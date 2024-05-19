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
        Schema::create('tiet_hocs', function (Blueprint $table) {
            $table->id();
            $table->string('Ten');
            $table->timestamp('ThoiGianBatDau');
            $table->timestamp('ThoiGianKetThuc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiet_hocs');
    }
};