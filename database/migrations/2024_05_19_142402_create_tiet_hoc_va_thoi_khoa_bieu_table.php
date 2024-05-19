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
        Schema::create('tiet_hoc_va_thoi_khoa_bieu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ThoiKhoaBieuID');
            $table->unsignedBigInteger('TietHocID');
            $table->timestamps();


            $table->foreign('ThoiKhoaBieuID')->references('id')->on('thoi_khoa_bieus')->onDelete('cascade');
            $table->foreign('TietHocID')->references('id')->on('tiet_hocs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiet_hoc_va_thoi_khoa_bieu');
    }
};