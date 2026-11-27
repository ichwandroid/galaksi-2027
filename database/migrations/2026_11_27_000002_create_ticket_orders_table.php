<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_orders', function (Blueprint $table): void {
            $table->id();
            $table->string('order_code')->unique();
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('buyer_phone', 32);
            $table->string('category', 50)->default('Reguler');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->string('status', 32)->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_orders');
    }
};
