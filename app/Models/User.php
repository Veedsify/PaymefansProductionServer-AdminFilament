<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * Class User
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string $user_id
 * @property string $username
 * @property bool $admin
 * @property string $role
 * @property bool $is_active
 * @property bool $is_verified
 * @property bool $is_email_verified
 * @property bool $is_model
 * @property string|null $email_verify_code
 * @property Carbon|null $email_verify_time
 * @property bool $is_phone_verified
 * @property string $phone
 * @property string|null $profile_image
 * @property string|null $profile_banner
 * @property string|null $bio
 * @property string|null $location
 * @property string|null $website
 * @property string|null $country
 * @property string|null $state
 * @property string|null $city
 * @property string|null $zip
 * @property string|null $post_watermark
 * @property int $total_followers
 * @property int $total_following
 * @property int $total_subscribers
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property bool $active_status
 *
 * @property Collection|Model[] $models
 * @property Collection|UserRepost[] $user_reposts
 * @property Collection|UserStory[] $user_stories
 * @property Collection|PostImpression[] $post_impressions
 * @property Collection|Post[] $posts
 * @property Collection|PostComment[] $post_comments
 * @property Collection|CommentImpression[] $comment_impressions
 * @property Collection|PostLike[] $post_likes
 * @property Collection|Follow[] $follows
 * @property Collection|Subscriber[] $subscribers
 * @property Collection|LiveStream[] $live_streams
 * @property Collection|PostCommentLike[] $post_comment_likes
 * @property Collection|PostShared[] $post_shareds
 * @property Collection|LiveStreamLike[] $live_stream_likes
 * @property Collection|LiveStreamView[] $live_stream_views
 * @property Collection|Notification[] $notifications
 * @property Collection|LiveStreamComment[] $live_stream_comments
 * @property Collection|ReportPost[] $report_posts
 * @property Collection|UserPoint[] $user_points
 * @property Collection|UserTransaction[] $user_transactions
 * @property Collection|ModelSubscriptionPack[] $model_subscription_packs
 * @property Collection|UserSubscriptionHistory[] $user_subscription_histories
 * @property Collection|UserSubscriptionCurrent[] $user_subscription_currents
 * @property Collection|ReportMessage[] $report_messages
 * @property Collection|PointConversionRate[] $point_conversion_rates
 * @property Collection|UserWallet[] $user_wallets
 * @property Collection|UserAttachment[] $user_attachments
 * @property Collection|UserBank[] $user_banks
 * @property Collection|Cart[] $carts
 * @property Collection|HelpContact[] $help_contacts
 * @property Collection|GroupMember[] $group_participants
 * @property Collection|WishList[] $wish_lists
 * @property Collection|Order[] $orders
 * @property Collection|BlockedGroupParticipant[] $blocked_group_participants
 * @property Collection|LoginHistory[] $login_histories
 * @property Collection|ActivityLog[] $activity_logs
 * @property Collection|Message[] $messages
 * @property Collection|ReportUser[] $report_users
 * @property Collection|Setting[] $settings
 * @property Collection|ReportLive[] $report_lives
 * @property Collection|ReportComment[] $report_comments
 * @property Collection|TwoFactorAuth[] $two_factor_auths
 * @property Collection|ResetPasswordRequest[] $reset_password_requests
 * @property Collection|UserWithdrawalBankAccount[] $user_withdrawal_bank_accounts
 * @property Collection|WithdrawalRequestCode[] $withdrawal_request_codes
 * @property Collection|WithdrawalRequest[] $withdrawal_requests
 *
 * @package App\Models
 */
class User extends Authenticatable implements FilamentUser
{
    use Notifiable, HasFactory;
    protected $table = "User";
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === "admin" || $this->role === "support";
    }

    protected $casts = [
        "admin" => "bool",
        "is_active" => "bool",
        "is_verified" => "bool",
        "is_email_verified" => "bool",
        "is_model" => "bool",
        "email_verify_time" => "datetime",
        "is_phone_verified" => "bool",
        "total_followers" => "int",
        "total_following" => "int",
        "total_subscribers" => "int",
        "active_status" => "bool",
        "can_create_content" => "bool",
        "can_comment" => "bool",
        "can_message" => "bool",
        "can_livestream" => "bool",
        "can_purchase" => "bool",
        "can_withdraw" => "bool",
        "can_receive_gifts" => "bool",
        "content_requires_approval" => "bool",
        "is_shadowbanned" => "bool",
        "can_report" => "bool",
        "immune_to_reports" => "bool",
        "can_create_groups" => "bool",
        "can_access_analytics" => "bool",
        "can_use_premium_features" => "bool",
        "additional_permissions" => "array",
        "flags" => "array",
    ];

    protected $hidden = ["password"];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->password = bcrypt($model->password);
            $model->user_id = Str::uuid();
        });
    }

    protected $fillable = [
        "email",
        "name",
        "password",
        "user_id",
        "username",
        "admin",
        "role",
        "is_active",
        "is_verified",
        "is_email_verified",
        "is_model",
        "email_verify_code",
        "email_verify_time",
        "is_phone_verified",
        "phone",
        "profile_image",
        "profile_banner",
        "bio",
        "location",
        "website",
        "country",
        "currency",
        "state",
        "city",
        "zip",
        "post_watermark",
        "total_followers",
        "total_following",
        "total_subscribers",
        "active_status",
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
        "flags",
    ];

    public function models()
    {
        return $this->hasMany(Model::class, "user_id");
    }

    public function user_reposts()
    {
        return $this->hasMany(UserRepost::class, "user_id");
    }

    public function user_stories()
    {
        return $this->hasMany(UserStory::class, "user_id");
    }

    public function post_impressions()
    {
        return $this->hasMany(PostImpression::class, "user_id");
    }

    public function posts()
    {
        return $this->hasMany(Post::class, "user_id");
    }

    public function post_comments()
    {
        return $this->hasMany(PostComment::class, "user_id");
    }

    public function comment_impressions()
    {
        return $this->hasMany(CommentImpression::class, "user_id");
    }

    public function post_likes()
    {
        return $this->hasMany(PostLike::class, "user_id");
    }

    public function follows()
    {
        return $this->hasMany(Follow::class, "user_id");
    }

    public function subscribers()
    {
        return $this->hasMany(Subscriber::class, "user_id");
    }

    public function live_streams()
    {
        return $this->hasMany(LiveStream::class, "user_id", "user_id");
    }

    public function post_comment_likes()
    {
        return $this->hasMany(PostCommentLike::class, "user_id");
    }

    public function post_shareds()
    {
        return $this->hasMany(PostShared::class, "user_id");
    }

    public function live_stream_likes()
    {
        return $this->hasMany(LiveStreamLike::class, "user_id");
    }

    public function live_stream_views()
    {
        return $this->hasMany(LiveStreamView::class, "user_id");
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, "user_id");
    }

    public function live_stream_comments()
    {
        return $this->hasMany(LiveStreamComment::class, "user_id");
    }

    public function report_posts()
    {
        return $this->hasMany(ReportPost::class, "user_id");
    }

    public function user_point()
    {
        return $this->hasOne(UserPoint::class, "user_id");
    }

    public function user_transactions()
    {
        return $this->hasMany(UserTransaction::class, "user_id");
    }

    public function model_subscription_packs()
    {
        return $this->hasMany(ModelSubscriptionPack::class, "user_id");
    }

    public function user_subscription_histories()
    {
        return $this->hasMany(UserSubscriptionHistory::class, "model_id");
    }

    public function user_subscription_currents()
    {
        return $this->hasMany(UserSubscriptionCurrent::class, "model_id");
    }

    public function report_messages()
    {
        return $this->hasMany(ReportMessage::class, "user_id");
    }

    public function point_conversion_rates()
    {
        return $this->belongsToMany(
            PointConversionRate::class,
            "PointConversionRateUsers",
            "user_id",
            "pointConversionRateId"
        )->withPivot("id");
    }

    public function user_wallets()
    {
        return $this->hasMany(UserWallet::class, "user_id");
    }

    public function user_attachments()
    {
        return $this->hasMany(UserAttachment::class, "user_id");
    }

    public function user_banks()
    {
        return $this->hasMany(UserBank::class, "user_id");
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, "user_id");
    }

    public function help_contacts()
    {
        return $this->hasMany(HelpContact::class, "user_id");
    }

    public function group_members()
    {
        return $this->hasMany(GroupMember::class, "user_id");
    }

    public function wish_lists()
    {
        return $this->hasMany(WishList::class, "user_id");
    }

    public function orders()
    {
        return $this->hasMany(Order::class, "user_id");
    }

    public function blocked_group_participants()
    {
        return $this->hasMany(BlockedGroupParticipant::class, "user_id");
    }

    public function login_histories()
    {
        return $this->hasMany(LoginHistory::class, "user_id");
    }

    public function activity_logs()
    {
        return $this->hasMany(ActivityLog::class, "user_id");
    }

    public function messages()
    {
        return $this->hasMany(Message::class, "receiver_id", "user_id");
    }

    public function report_users()
    {
        return $this->hasMany(ReportUser::class, "user_id");
    }

    public function settings()
    {
        return $this->hasOne(Setting::class, "user_id");
    }

    public function report_lives()
    {
        return $this->hasMany(ReportLive::class, "user_id");
    }

    public function report_comments()
    {
        return $this->hasMany(ReportComment::class, "user_id");
    }

    public function two_factor_auths()
    {
        return $this->hasMany(TwoFactorAuth::class, "user_id");
    }

    public function reset_password_requests()
    {
        return $this->hasMany(ResetPasswordRequest::class, "user_id");
    }

    public function user_withdrawal_bank_accounts()
    {
        return $this->hasMany(UserWithdrawalBankAccount::class, "user_id");
    }

    public function withdrawal_request_codes()
    {
        return $this->hasMany(WithdrawalRequestCode::class, "user_id");
    }

    public function withdrawal_requests()
    {
        return $this->hasMany(WithdrawalRequest::class, "user_id");
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, "user_id");
    }

    public function supportTicketReplies()
    {
        return $this->hasMany(SupportTicketReply::class, "user_id");
    }
}
