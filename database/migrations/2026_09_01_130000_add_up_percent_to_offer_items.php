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
        if (Schema::hasTable('offer_items') && !Schema::hasColumn('offer_items', 'up_percent')) {
            Schema::table('offer_items', function (Blueprint $table) {
                $table->decimal('up_percent', 8, 2)->default(40)->after('base_price_per_liter');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('offer_items') && Schema::hasColumn('offer_items', 'up_percent')) {
            Schema::table('offer_items', function (Blueprint $table) {
                $table->dropColumn('up_percent');
            });
        }
    }
};
