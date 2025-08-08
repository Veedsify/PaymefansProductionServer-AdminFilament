<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GroupMember
 *
 * @property int $id
 * @property int $user_id
 * @property int $group_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property User $user
 * @property Group $group
 *
 * @package App\Models
 */
class GroupMember extends Model
{
    protected $table = "GroupMember";

    protected $casts = [
        "userId" => "int",
        "groupId" => "int",
    ];

    protected $fillable = [
        "userId",
        "groupId",
        "role",
        "joinedAt",
        "lastSeen",
        "isMuted",
        "isBlocked",
        "mutedBy",
        "mutedUntil",
        "created_at",
        "updated_at",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "userId");
    }

    public function mutedBy()
    {
        return $this->belongsTo(User::class, "mutedBy");
    }

    public function group()
    {
        return $this->belongsTo(Group::class, "groupId");
    }
}
