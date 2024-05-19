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
        Schema::create('phan_quyens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('VaiTroID');
            $table->unsignedBigInteger('QuyenHanID');
            $table->timestamps();

            $table->foreign('VaiTroID')->references('id')->on('vai_tros')->onDelete('cascade');
            $table->foreign('QuyenHanID')->references('id')->on('quyen_hans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phan_quyens');
    }
};