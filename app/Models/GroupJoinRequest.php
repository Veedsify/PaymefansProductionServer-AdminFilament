<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupJoinRequest extends Model
{
    protected $table = 'GroupJoinRequests';

    protected $casts = [
        'groupId' => 'int',
        'userId' => 'int',
    ];

    protected $fillable = [
        'groupId',
        'userId',
        'message',
        'status',
        'created_at',
        'updated_at',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
