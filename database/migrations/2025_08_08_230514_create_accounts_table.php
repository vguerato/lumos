<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('accounts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('balance', 14, 2)->default(0);
            $table->timestamps();
            $table->unique(['user_id','name']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('accounts');
    }
};
