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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'packing_size')) {
                $table->string('packing_size')->nullable()->after('nama_produk');
            }
            if (!Schema::hasColumn('products', 'price_per_l')) {
                $table->unsignedBigInteger('price_per_l')->default(0)->after('packing_size');
            }
            if (Schema::hasColumn('products', 'performa')) {
                $table->string('performa')->nullable()->change();
            }
            if (Schema::hasColumn('products', 'kriteria')) {
                $table->string('kriteria')->nullable()->change();
            }
            if (Schema::hasColumn('products', 'hasil_akhir')) {
                $table->string('hasil_akhir')->nullable()->change();
            }
            if (Schema::hasColumn('products', 'harga')) {
                $table->bigInteger('harga')->nullable()->change();
            }
        });

        if (!Schema::hasTable('product_batches')) {
            Schema::create('product_batches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->string('batch_number');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_batches');

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'packing_size')) {
                $table->dropColumn('packing_size');
            }
            if (Schema::hasColumn('products', 'price_per_l')) {
                $table->dropColumn('price_per_l');
            }
        });
    }
};
