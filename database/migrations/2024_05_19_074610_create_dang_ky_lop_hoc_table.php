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
        Schema::create('dang_ky_lop_hocs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('NgayDangKy');
            $table->unsignedBigInteger('ThoiKhoaBieuID');
            $table->unsignedBigInteger('TaiKhoanID');
            $table->string('Status');
            $table->timestamps();

            $table->foreign('ThoiKhoaBieuID')->references('id')->on('thoi_khoa_bieus')->onDelete('cascade');
            $table->foreign('TaiKhoanID')->references('id')->on('tai_khoans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dang_ky_lop_hocs');
    }
};