<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignIdFor(Category::class)->constrained();
            $table->string('name', 50); // cột name có độ dài 50 kí tự
            $table->string('image', 255); // cột name có độ dài 50 kí tự
            $table->dateTime('startday'); // cột ngayphathanh kiểu dateTime
            $table->dateTime('enday'); // cột ngayketthuc kiểu dateTime
            $table->string('address', 100); // cột diachi có độ dài 100 kí tự
            $table->decimal('price', 8, 2); // cột giatien kiểu decimal với tối đa 8 chữ số và 2 chữ số sau dấu phẩy
            $table->string('description', 250); // cột mô tả có độ dài 250 kí tự
            $table->string('nguoitochuc')->nullable(); // cột người tổ chức có thể null
            $table->string('noitochuc')->nullable(); // cột nơi tổ chức có thể null
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
        Schema::dropIfExists('tickets');
    }
};
