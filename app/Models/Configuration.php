<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Configuration extends Model
{
    protected $table = "Configurations";

    protected $fillable = [
        'app_name',
        'app_version',
        'app_description',
        'app_logo',
        'app_url',
        // Currency Settings
        "default_currency",
        "default_rate",
        "default_symbol",
        // Point Conversion Settings
        "point_conversion_rate",
        "point_conversion_rate_ngn",
        // Withdrawal Settings
        "min_withdrawal_amount",
        "min_withdrawal_amount_ngn",
        // Desposit Settings
        "min_deposit_amount",
        "min_deposit_amount_ngn",
        // Platform Settings
        "platform_deposit_fee",
        "platform_withdrawal_fee",
        // Theme Settings
        "default_mode",
        "primary_color",
        "secondary_color",
        "accent_color",
        // Pagination Settings
        "home_feed_limit",
        "personal_profile_limit",
        "personal_media_limit",
        "personal_repost_limit",
        "post_page_comment_limit",
        "post_page_comment_reply_limit",
        "other_user_profile_limit",
        "other_user_media_limit",
        "other_user_repost_limit",
        "notification_limit",
        "transaction_limit",
        // Model Search Settings
        "model_search_limit",
        // Messaging Settings 
        "conversation_limit",
        "message_limit",
        // Group Settings 
        "group_message_limit",
        "group_participant_limit",
        "group_limit",
        // Hookup Settings 
        "hookup_enabled",
        "hookup_page_limit",
        // Status Settings 
        "status_limit",
        // Subscription Settings 
        "subscription_limit",
        "subscribers_limit",
        "active_subscribers_limit",
        // Follower Settings
        "followers_limit",
        // User Media Settings
        "upload_media_limit",
        // Model Media Settings
        "model_upload_media_limit",
        // Success/Error Messages
        "profile_updated_success_message",
        "profile_updated_error_message",
        "profile_updating_message",
        "profile_image_updated_success_message",
        "profile_image_updated_error_message",
        "profile_image_updating_message",
        "point_purchase_success_message",
        "point_purchase_error_message",
        "point_purchasing_message",
        "point_purchase_minimum_message",
    ];
}
