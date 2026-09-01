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
        Schema::table('soas', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false)->after('tanggal_soa');
            $table->decimal('paid_amount', 20, 2)->default(0)->after('is_paid');
            $table->string('payment_receipt')->nullable()->after('paid_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soas', function (Blueprint $table) {
            $table->dropColumn(['is_paid', 'paid_amount', 'payment_receipt']);
        });
    }
};
