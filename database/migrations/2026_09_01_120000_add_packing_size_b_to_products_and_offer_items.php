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
        if (!Schema::hasColumn('products', 'packing_size_b')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('packing_size_b')->nullable()->after('comp_b');
            });
        }

        if (Schema::hasTable('offer_items') && !Schema::hasColumn('offer_items', 'packing_size_b')) {
            Schema::table('offer_items', function (Blueprint $table) {
                $table->string('packing_size_b')->nullable()->after('comp_b');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'packing_size_b')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('packing_size_b');
            });
        }

        if (Schema::hasTable('offer_items') && Schema::hasColumn('offer_items', 'packing_size_b')) {
            Schema::table('offer_items', function (Blueprint $table) {
                $table->dropColumn('packing_size_b');
            });
        }
    }
};
