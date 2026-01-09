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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'card'])->default('cash')->after('total');
            $table->decimal('received_amount', 10, 2)->default(0)->after('payment_method');
            $table->decimal('change_amount', 10, 2)->default(0)->after('received_amount');
            $table->boolean('is_return')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'received_amount', 'change_amount', 'is_return']);
        });
    }
};
