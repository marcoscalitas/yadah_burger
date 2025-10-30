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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            // Client data (useful for guest orders)
            $table->string('customer_name', 100)->nullable();
            $table->string('customer_phone', 9)->nullable();
            $table->boolean('pickup_in_store')->default(false);
            $table->string('address_1', 255)->nullable();
            $table->string('address_2', 255)->nullable();
            $table->text('notes')->nullable();
            $table->enum('payment_method', ['cash', 'transfer', 'tpa'])->default('cash');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('whatsapp_message')->nullable();
            $table->enum('order_status', ['p', 'st', 'c', 'd', 'x'])->default('p');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
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
