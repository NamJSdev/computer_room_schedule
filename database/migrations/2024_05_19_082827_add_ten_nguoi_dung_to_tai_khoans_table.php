<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTenNguoiDungToTaiKhoansTable extends Migration
{
    public function up()
    {
        Schema::table('tai_khoans', function (Blueprint $table) {
            $table->string('TenNguoiDung')->after('Email');
        });
    }

    public function down()
    {
        Schema::table('tai_khoans', function (Blueprint $table) {
            $table->dropColumn('TenNguoiDung');
        });
    }
}