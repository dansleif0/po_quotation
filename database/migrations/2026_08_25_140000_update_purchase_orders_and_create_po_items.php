<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'supplier_name')) {
                $table->string('supplier_name')->default('PT CIPTA MARITIM PERKASA')->after('nama_klien');
            }
            if (!Schema::hasColumn('purchase_orders', 'supplier_address')) {
                $table->string('supplier_address', 500)->default('Ruko Tunas Regency Blok A5 No 09 – 10 Tanjung Uncang')->after('supplier_name');
            }
            if (!Schema::hasColumn('purchase_orders', 'deliver_to_name')) {
                $table->string('deliver_to_name')->default('PT TASNIEM GERAI INSPIRASI')->after('supplier_address');
            }
            if (!Schema::hasColumn('purchase_orders', 'deliver_to_address')) {
                $table->string('deliver_to_address', 500)->default('Komp. Ruko KDA Junction Blok C 8-9')->after('deliver_to_name');
            }
            if (!Schema::hasColumn('purchase_orders', 'currency')) {
                $table->string('currency')->default('IDR')->after('deliver_to_address');
            }
            if (!Schema::hasColumn('purchase_orders', 'delivery_date')) {
                $table->string('delivery_date')->default('-')->after('currency');
            }
            if (!Schema::hasColumn('purchase_orders', 'offer_letter')) {
                $table->string('offer_letter')->nullable()->after('delivery_date');
            }
            if (!Schema::hasColumn('purchase_orders', 'payment_term')) {
                $table->string('payment_term')->default('BANK TRANSFER')->after('offer_letter');
            }
            if (!Schema::hasColumn('purchase_orders', 'job_project')) {
                $table->string('job_project')->nullable()->after('payment_term');
            }
            if (!Schema::hasColumn('purchase_orders', 'issued_by')) {
                $table->string('issued_by')->default('Ardian Wijaya Kusuma')->after('job_project');
            }
            if (!Schema::hasColumn('purchase_orders', 'approved_by')) {
                $table->string('approved_by')->default('Samsu Rizal')->after('issued_by');
            }
        });

        if (!Schema::hasTable('purchase_order_items')) {
            Schema::create('purchase_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
                $table->string('nama_produk');
                $table->string('packing_size')->nullable();
                $table->double('qty_order')->default(1);
                $table->double('consumption_l')->default(1);
                $table->decimal('price_per_liter', 15, 2)->default(0);
                $table->decimal('total_price', 15, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
