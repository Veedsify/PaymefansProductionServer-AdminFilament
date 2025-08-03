<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Group
 *
 * @property int $id
 * @property string $group_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Collection|BlockedGroupParticipant[] $blocked_group_participants
 * @property Collection|GroupMember[] $group_participants
 * @property GroupSetting $group_setting
 *
 * @package App\Models
 */
class Group extends Model
{
    protected $table = "Groups";

    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";

    protected $casts = [
        "adminId" => "int",
    ];

    protected $fillable = [
        "adminId",
        "description",
        "groupIcon",
        "groupType",
        "isActive",
        "maxMembers",
        "name",
        "created_at",
        "updateAt",
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, "adminId");
    }

    public function blocked_group_participants()
    {
        return $this->hasMany(BlockedGroupParticipant::class);
    }
    public function members()
    {
        return $this->hasMany(GroupMember::class, "groupId");
    }

    public function messages()
    {
        return $this->hasMany(GroupMessage::class, "groupId");
    }

    public function settings()
    {
        return $this->hasOne(GroupSetting::class, "groupId");
    }

    public function joinRequests()
    {
        return $this->hasMany(GroupJoinRequest::class, "groupId");
    }

    public function GroupInvitations()
    {
        return $this->hasMany(GroupInvitation::class, "groupId");
    }
}
