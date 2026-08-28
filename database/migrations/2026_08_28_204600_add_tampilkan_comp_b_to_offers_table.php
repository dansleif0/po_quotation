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
        Schema::table('offers', function (Blueprint $table) {
            if (!Schema::hasColumn('offers', 'tampilkan_comp_b')) {
                $table->boolean('tampilkan_comp_b')->default(false)->after('hilangkan_grand_total');
            }
        });

        Schema::table('offer_items', function (Blueprint $table) {
            if (!Schema::hasColumn('offer_items', 'comp_b')) {
                $table->string('comp_b')->nullable()->after('nama_produk');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            if (Schema::hasColumn('offers', 'tampilkan_comp_b')) {
                $table->dropColumn('tampilkan_comp_b');
            }
        });

        Schema::table('offer_items', function (Blueprint $table) {
            if (Schema::hasColumn('offer_items', 'comp_b')) {
                $table->dropColumn('comp_b');
            }
        });
    }
};
