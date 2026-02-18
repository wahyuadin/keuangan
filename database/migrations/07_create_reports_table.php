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
            // $table->foreignUuid('branch_id')->nullable()->constrained('branchoffices')->nullOnDelete();
            $table->foreignUuid('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->foreignUuid('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->foreignUuid('sla_id')->nullable()->constrained('slas')->nullOnDelete();
            $table->string('januari')->default(0);
            $table->string('januari_verif_by')->nullable(); // x
            $table->string('januari_verif_by_ho')->nullable(); // x
            $table->string('januari_realisasi')->nullable(); // v
            $table->string('januari_realisasi_by_ho')->nullable(); // v
            $table->text('januari_keterangan')->nullable(); // v
            $table->text('januari_keterangan_by_ho')->nullable(); // v
            $table->string('februari')->default(0);
            $table->string('februari_verif_by')->nullable();
            $table->string('februari_verif_by_ho')->nullable(); // x
            $table->string('februari_realisasi')->nullable();
            $table->string('februari_realisasi_by_ho')->nullable();
            $table->text('februari_keterangan')->nullable();
            $table->text('februari_keterangan_by_ho')->nullable();
            $table->string('maret')->default(0);
            $table->string('maret_verif_by')->nullable();
            $table->string('maret_verif_by_ho')->nullable(); // x
            $table->string('maret_realisasi')->nullable();
            $table->string('maret_realisasi_by_ho')->nullable();
            $table->text('maret_keterangan')->nullable();
            $table->text('maret_keterangan_by_ho')->nullable();
            $table->string('april')->default(0);
            $table->string('april_verif_by')->nullable();
            $table->string('april_verif_by_ho')->nullable(); // x
            $table->string('april_realisasi')->nullable();
            $table->string('april_realisasi_by_ho')->nullable();
            $table->text('april_keterangan')->nullable();
            $table->text('april_keterangan_by_ho')->nullable();
            $table->string('mei')->default(0);
            $table->string('mei_verif_by')->nullable();
            $table->string('mei_verif_by_ho')->nullable(); // x
            $table->string('mei_realisasi')->nullable();
            $table->string('mei_realisasi_by_ho')->nullable();
            $table->text('mei_keterangan')->nullable();
            $table->text('mei_keterangan_by_ho')->nullable();
            $table->string('juni')->default(0);
            $table->string('juni_verif_by')->nullable();
            $table->string('juni_verif_by_ho')->nullable(); // x
            $table->string('juni_realisasi')->nullable();
            $table->string('juni_realisasi_by_ho')->nullable();
            $table->text('juni_keterangan')->nullable();
            $table->text('juni_keterangan_by_ho')->nullable();
            $table->string('juli')->default(0);
            $table->string('juli_verif_by')->nullable();
            $table->string('juli_verif_by_ho')->nullable(); // x
            $table->string('juli_realisasi')->nullable();
            $table->string('juli_realisasi_by_ho')->nullable();
            $table->text('juli_keterangan')->nullable();
            $table->text('juli_keterangan_by_ho')->nullable();
            $table->string('agustus')->default(0);
            $table->string('agustus_verif_by')->nullable();
            $table->string('agustus_verif_by_ho')->nullable(); // x
            $table->string('agustus_realisasi')->nullable();
            $table->string('agustus_realisasi_by_ho')->nullable();
            $table->text('agustus_keterangan')->nullable();
            $table->text('agustus_keterangan_by_ho')->nullable();
            $table->string('september')->default(0);
            $table->string('september_verif_by')->nullable();
            $table->string('september_verif_by_ho')->nullable(); // x
            $table->string('september_realisasi')->nullable();
            $table->string('september_realisasi_by_ho')->nullable();
            $table->text('september_keterangan')->nullable();
            $table->text('september_keterangan_by_ho')->nullable();
            $table->string('oktober')->default(0);
            $table->string('oktober_verif_by')->nullable();
            $table->string('oktober_verif_by_ho')->nullable(); // x
            $table->string('oktober_realisasi')->nullable();
            $table->string('oktober_realisasi_by_ho')->nullable();
            $table->text('oktober_keterangan')->nullable();
            $table->text('oktober_keterangan_by_ho')->nullable();
            $table->string('november')->default(0);
            $table->string('november_verif_by')->nullable();
            $table->string('november_verif_by_ho')->nullable(); // x
            $table->string('november_realisasi')->nullable();
            $table->string('november_realisasi_by_ho')->nullable();
            $table->text('november_keterangan')->nullable();
            $table->text('november_keterangan_by_ho')->nullable();
            $table->string('desember')->default(0);
            $table->string('desember_verif_by')->nullable();
            $table->string('desember_verif_by_ho')->nullable(); // x
            $table->string('desember_realisasi')->nullable();
            $table->string('desember_realisasi_by_ho')->nullable();
            $table->text('desember_keterangan')->nullable();
            $table->text('desember_keterangan_by_ho')->nullable();
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
