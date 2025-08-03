<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupInvitation extends Model
{
    protected $table = 'GroupInvitations';

    protected $casts = [
        'groupId' => 'int',
        'userId' => 'int',
    ];

    protected $fillable = [
        'groupId',
        'inviterId',
        'inviteeId',
        'message',
        'status',
        'expiresAt',
        'created_at',
        'updated_at',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupId');
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviterId');
    }
    public function invitee()
    {
        return $this->belongsTo(User::class, 'inviteeId');
    }
}
