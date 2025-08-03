<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    protected $table = "GroupMessage";

    protected $casts = [
        "groupId" => "int",
        "senderId" => "int",
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];

    protected $fillable = [
        "content",
        "senderId",
        "groupId",
        "isEdited",
        "messageType",
        "replyToId",
        "editedAt",
        "created_at",
        "updated_at",
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, "senderId");
    }

    public function group()
    {
        return $this->belongsTo(Group::class, "groupId");
    }

    public function replyTo()
    {
        return $this->belongsTo(GroupMessage::class, "replyToId");
    }

    public function replies()
    {
        return $this->hasMany(GroupMessage::class);
    }

    public function attachments()
    {
        return $this->hasMany(GroupMessageAttachment::class, "messageId");
    }

    public function GroupMessageRead()
    {
        return $this->hasMany(GroupMessageRead::class, "messageId");
    }
}
