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
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('branch_id')->nullable()->constrained('branchoffices')->nullOnDelete();
            $table->foreignUuid('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->foreignUuid('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->string('januari')->default(0);
            $table->string('januari_verif_by')->nullable(); // x
            $table->string('januari_selisih')->nullable(); // v
            $table->string('januari_keterangan')->nullable(); // v
            $table->string('februari')->default(0);
            $table->string('februari_verif_by')->nullable();
            $table->string('februari_selisih')->nullable();
            $table->string('februari_keterangan')->nullable();
            $table->string('maret')->default(0);
            $table->string('maret_verif_by')->nullable();
            $table->string('maret_selisih')->nullable();
            $table->string('maret_keterangan')->nullable();
            $table->string('april')->default(0);
            $table->string('april_verif_by')->nullable();
            $table->string('april_selisih')->nullable();
            $table->string('april_keterangan')->nullable();
            $table->string('mei')->default(0);
            $table->string('mei_verif_by')->nullable();
            $table->string('mei_selisih')->nullable();
            $table->string('mei_keterangan')->nullable();
            $table->string('juni')->default(0);
            $table->string('juni_verif_by')->nullable();
            $table->string('juni_selisih')->nullable();
            $table->string('juni_keterangan')->nullable();
            $table->string('juli')->default(0);
            $table->string('juli_verif_by')->nullable();
            $table->string('juli_selisih')->nullable();
            $table->string('juli_keterangan')->nullable();
            $table->string('agustus')->default(0);
            $table->string('agustus_verif_by')->nullable();
            $table->string('agustus_selisih')->nullable();
            $table->string('agustus_keterangan')->nullable();
            $table->string('september')->default(0);
            $table->string('september_verif_by')->nullable();
            $table->string('september_selisih')->nullable();
            $table->string('september_keterangan')->nullable();
            $table->string('oktober')->default(0);
            $table->string('oktober_verif_by')->nullable();
            $table->string('oktober_selisih')->nullable();
            $table->string('oktober_keterangan')->nullable();
            $table->string('november')->default(0);
            $table->string('november_verif_by')->nullable();
            $table->string('november_selisih')->nullable();
            $table->string('november_keterangan')->nullable();
            $table->string('desember')->default(0);
            $table->string('desember_verif_by')->nullable();
            $table->string('desember_selisih')->nullable();
            $table->string('desember_keterangan')->nullable();
            $table->string('tahun')->nullable();
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
        Schema::dropIfExists('reports');
    }
};
