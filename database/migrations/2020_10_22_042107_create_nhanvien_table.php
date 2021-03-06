<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNhanvienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhanvien', function (Blueprint $table) {
            $table->bigIncrements('nv_id');
            $table->string('nv_hoten');
            $table->string('nv_sdt');
            $table->string('username');
            $table->string('password');
            //khoi tao khoa ngoai
            $table->bigInteger('q_id')->unsigned();
            $table->foreign('q_id')->references('q_id')->on('quyen')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nhanvien');
    }
}
