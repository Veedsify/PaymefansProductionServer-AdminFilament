<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 * 
 * @property int $id
 * @property string $message_id
 * @property string $sender_id
 * @property string $receiver_id
 * @property bool $seen
 * @property string $message
 * @property array|null $attachment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $conversationsId
 * 
 * @property Conversation|null $conversation
 * @property User $user
 * @property Collection|ReportMessage[] $report_messages
 *
 * @package App\Models
 */
class Message extends Model
{
	protected $table = 'Messages';

	protected $casts = [
		'seen' => 'bool',
		'attachment' => 'json'
	];

	protected $fillable = [
		'message_id',
		'sender_id',
		'receiver_id',
		'seen',
		'message',
		'attachment',
		'conversationsId'
	];

	public function conversation()
	{
		return $this->belongsTo(Conversation::class, 'conversationsId', 'conversation_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'sender_id', 'user_id');
	}

	public function report_messages()
	{
		return $this->hasMany(ReportMessage::class, 'message_id');
	}
}
