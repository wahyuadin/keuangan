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
        Schema::create('slas', function (Blueprint $table) {
            $table->unique(['item_id', 'clinic_id', 'tahun']);
            $table->uuid('id')->primary();
            $table->foreignUuid('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->foreignUuid('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->string('rkap')->nullable();
            $table->string('tahun')->nullable();
            $table->text('create_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['item_id', 'clinic_id', 'tahun', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slas');
    }
};
