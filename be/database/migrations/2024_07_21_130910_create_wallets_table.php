<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Wallets', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('wallet', 100); // cột wallet có độ dài 100 kí tự
            $table->string('email', 50)->nullable(); // cột gmail có độ dài 50 kí tự
            $table->tinyInteger('role')->default(0); // 0: user, 1: nhân viên, 2: admin
            $table->timestamps(); // tự động tạo cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Wallets');
    }
}
