<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThoiKhoaBieuCrawTable extends Migration
{
    public function up()
    {
        Schema::create('thoi_khoa_bieu_craws', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('PhongMayID');
            $table->unsignedBigInteger('HocKyID');
            $table->string('TenMonHoc');
            $table->string('MaMonHoc');
            $table->string('Lop');
            $table->string('Thu');
            $table->string('NhomMH');
            $table->string('SoTinChi');
            $table->string('TuanHoc');
            $table->string('TietHoc');
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('PhongMayID')->references('id')->on('phong_mays')->onDelete('cascade');
            $table->foreign('HocKyID')->references('id')->on('hoc_kys')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('thoi_khoa_bieu_craws');
    }
}