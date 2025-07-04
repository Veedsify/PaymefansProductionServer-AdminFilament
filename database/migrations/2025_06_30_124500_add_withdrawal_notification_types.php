<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new enum values for withdrawal notifications
        DB::statement("ALTER TYPE \"NotificationTypes\" ADD VALUE IF NOT EXISTS 'withdrawal_approved'");
        DB::statement("ALTER TYPE \"NotificationTypes\" ADD VALUE IF NOT EXISTS 'withdrawal_rejected'");
        DB::statement("ALTER TYPE \"NotificationTypes\" ADD VALUE IF NOT EXISTS 'points_restored'");
        DB::statement("ALTER TYPE \"NotificationTypes\" ADD VALUE IF NOT EXISTS 'withdrawal_pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: PostgreSQL doesn't support removing enum values directly
        // You would need to recreate the enum type if you want to remove values
        // For now, we'll leave this empty as it's safer to keep the values
    }
};
