<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SupportTicketReply
 * 
 * @property int $id
 * @property string $ticket_id
 * @property int $user_id
 * @property string $message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property SupportTicket $supportTicket
 * @property User $user
 *
 * @package App\Models
 */
class SupportTicketReply extends Model
{
    protected $table = 'SupportTicketReplies';

    protected $casts = [
        'user_id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message'
    ];

    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id', 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
