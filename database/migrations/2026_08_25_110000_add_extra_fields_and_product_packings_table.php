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
            if (!Schema::hasColumn('products', 'generic')) {
                $table->string('generic')->nullable()->after('nama_produk');
            }
            if (!Schema::hasColumn('products', 'primer_topcoat')) {
                $table->string('primer_topcoat')->nullable()->after('generic');
            }
            if (!Schema::hasColumn('products', 'category')) {
                $table->string('category')->nullable()->after('primer_topcoat');
            }
            if (!Schema::hasColumn('products', 'thinner')) {
                $table->string('thinner')->nullable()->after('category');
            }
        });

        if (!Schema::hasTable('product_packings')) {
            Schema::create('product_packings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->string('packing_size');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_packings');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['generic', 'primer_topcoat', 'category', 'thinner']);
        });
    }
};
