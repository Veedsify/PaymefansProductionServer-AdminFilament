<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SupportTicket
 * 
 * @property int $id
 * @property string $ticket_id
 * @property int $user_id
 * @property string $subject
 * @property string $message
 * @property string $statusP
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Collection|SupportTicketReply[] $supportTicketReplies
 *
 * @package App\Models
 */
class SupportTicket extends Model
{
    protected $table = 'SupportTickets';

    protected $casts = [
        'user_id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'ticket_id',
        'user_id',
        'subject',
        'message',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supportTicketReplies()
    {
        return $this->hasMany(SupportTicketReply::class, 'ticket_id', 'ticket_id');
    }

    public function getStatusBadgeColorAttribute()
    {
        return match ($this->status) {
            'open' => 'success',
            'pending' => 'warning',
            'closed' => 'danger',
            default => 'gray'
        };
    }

    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status);
    }
}
