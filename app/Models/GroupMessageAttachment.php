<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMessageAttachment extends Model
{
    protected $table = "GroupMessageAttachment";
    protected $casts = [
        "messageId" => "int",
        "created_at" => "datetime",
    ];

    protected $fillable = [
        "id",
        "url",
        "type",
        "messageId",
        "fileName",
        "fileSize",
        "created_at",
    ];

    public function message()
    {
        return $this->belongsTo(GroupMessage::class, "messageId");
    }
}
