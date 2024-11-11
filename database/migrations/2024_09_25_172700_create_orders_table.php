<?php

use App\Models\Product;
use App\Models\Ship_address;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->unsignedInteger('quantity');
            $table->decimal('total_amount', 10, 2);
            $table->tinyInteger('status')->default(0);//0: Đang chờ xử lí, 1: Đã xử lí/ đang chuẩn bị sản phẩm, 2: Đang vận chuyển, 3: Giao hàng thành công, 4: Đơn hàng đã bị hủy, 5: Đơn hàng đã được trả lại bởi người dung
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
