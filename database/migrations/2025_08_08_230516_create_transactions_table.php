<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->enum('type', ['deposit', 'withdrawal', 'transfer_in', 'transfer_out']);
            $table->decimal('amount', 14, 2);
            $table->string('description')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('completed_at')->nullable();
            $table->timestampTz('cancelled_at')->nullable();
            $table->timestampTz('updated_at')->useCurrentOnUpdate();
        });
    }
    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};
