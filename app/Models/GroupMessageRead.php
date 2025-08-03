<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMessageRead extends Model
{
    protected $table = "GroupMessageRead";

    protected $casts = [
        "messageId" => "int",
        "userId" => "int",
        "readAt" => "datetime",
    ];

    protected $fillable = [
        "messageId",
        "userId",
        "readAt",
    ];

    public function message()
    {
        return $this->belongsTo(GroupMessage::class, "messageId");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "userId");
    }
}
