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
            if (!Schema::hasColumn('offers', 'no_surat')) {
                $table->string('no_surat')->nullable()->after('id');
            }
            if (!Schema::hasColumn('offers', 'project_no')) {
                $table->string('project_no')->nullable()->after('no_surat');
            }
            if (!Schema::hasColumn('offers', 'client_id')) {
                $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete()->after('project_no');
            }
        });

        Schema::table('offer_items', function (Blueprint $table) {
            if (!Schema::hasColumn('offer_items', 'product_id')) {
                $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete()->after('offer_id');
            }
            if (!Schema::hasColumn('offer_items', 'packing_size')) {
                $table->string('packing_size')->nullable()->after('nama_produk');
            }
            if (!Schema::hasColumn('offer_items', 'qty_order')) {
                $table->decimal('qty_order', 10, 2)->default(0)->after('packing_size');
            }
            if (!Schema::hasColumn('offer_items', 'consumption_l')) {
                $table->decimal('consumption_l', 10, 2)->default(0)->after('qty_order');
            }
            if (!Schema::hasColumn('offer_items', 'status_produk')) {
                $table->string('status_produk')->nullable()->after('consumption_l');
            }
            if (!Schema::hasColumn('offer_items', 'price_per_liter')) {
                $table->bigInteger('price_per_liter')->default(0)->after('status_produk');
            }
            if (!Schema::hasColumn('offer_items', 'base_price_per_liter')) {
                $table->bigInteger('base_price_per_liter')->default(0)->after('price_per_liter');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            if (Schema::hasColumn('offers', 'client_id')) {
                $table->dropForeign(['client_id']);
                $table->dropColumn('client_id');
            }
            if (Schema::hasColumn('offers', 'no_surat')) {
                $table->dropColumn('no_surat');
            }
            if (Schema::hasColumn('offers', 'project_no')) {
                $table->dropColumn('project_no');
            }
        });

        Schema::table('offer_items', function (Blueprint $table) {
            if (Schema::hasColumn('offer_items', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            $table->dropColumn([
                'packing_size',
                'qty_order',
                'consumption_l',
                'status_produk',
                'price_per_liter',
                'base_price_per_liter',
            ]);
        });
    }
};
