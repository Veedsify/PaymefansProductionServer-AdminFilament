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
        Schema::table('WithdrawalRequest', function (Blueprint $table) {
            if (!Schema::hasColumn('WithdrawalRequest', 'transfer_code')) {
                $table->string('transfer_code')->nullable()->after('status');
            }
            if (!Schema::hasColumn('WithdrawalRequest', 'paystack_response')) {
                $table->json('paystack_response')->nullable()->after('reference');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('WithdrawalRequest', function (Blueprint $table) {
            $table->dropColumn(['transfer_code', 'paystack_response']);
        });
    }
};
