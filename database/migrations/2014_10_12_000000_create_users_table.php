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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->foreignUuid('branch_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignUuid('branch_id')->nullable()->constrained('branchoffices')->nullOnDelete();
            $table->string('username')->unique();
            $table->enum('is_active', ['0', '1'])->default('1');
            $table->enum('role', ['0', '1', '2'])->default('0'); // 0 hub_ppk 1 keuangan 2 superadmin
            $table->string('avatar')->default('default.png');
            $table->string('nama');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
