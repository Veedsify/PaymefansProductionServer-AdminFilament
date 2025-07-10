<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("User", function (Blueprint $table) {
            // Content creation permissions
            $table->boolean("can_create_content")->default(true)->after("role");
            $table
                ->boolean("can_comment")
                ->default(true)
                ->after("can_create_content");
            $table->boolean("can_message")->default(true)->after("can_comment");
            $table
                ->boolean("can_livestream")
                ->default(false)
                ->after("can_message");

            // Financial permissions
            $table
                ->boolean("can_purchase")
                ->default(true)
                ->after("can_livestream");
            $table
                ->boolean("can_withdraw")
                ->default(false)
                ->after("can_purchase");
            $table
                ->boolean("can_receive_gifts")
                ->default(true)
                ->after("can_withdraw");

            // Moderation permissions
            $table
                ->boolean("content_requires_approval")
                ->default(false)
                ->after("can_receive_gifts");
            $table
                ->boolean("is_shadowbanned")
                ->default(false)
                ->after("content_requires_approval");
            $table
                ->boolean("can_report")
                ->default(true)
                ->after("is_shadowbanned");
            $table
                ->boolean("immune_to_reports")
                ->default(false)
                ->after("can_report");

            // Platform features
            $table
                ->boolean("can_create_groups")
                ->default(true)
                ->after("immune_to_reports");
            $table
                ->boolean("can_access_analytics")
                ->default(false)
                ->after("can_create_groups");
            $table
                ->boolean("can_use_premium_features")
                ->default(false)
                ->after("can_access_analytics");

            // Store additional permissions as JSON for future extensibility
            $table
                ->json("additional_permissions")
                ->nullable()
                ->after("can_use_premium_features");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("User", function (Blueprint $table) {
            $table->dropColumn([
                "can_create_content",
                "can_comment",
                "can_message",
                "can_livestream",
                "can_purchase",
                "can_withdraw",
                "can_receive_gifts",
                "content_requires_approval",
                "is_shadowbanned",
                "can_report",
                "immune_to_reports",
                "can_create_groups",
                "can_access_analytics",
                "can_use_premium_features",
                "additional_permissions",
            ]);
        });
    }
};
