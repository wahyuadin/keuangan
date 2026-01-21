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
        Schema::create('rkaps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->string('jumlah');
            $table->text('create_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rkaps');
    }
};
