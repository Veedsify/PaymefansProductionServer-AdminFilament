<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Conversation
 * 
 * @property int $id
 * @property string $conversation_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Message[] $messages
 * @property Collection|Participant[] $participants
 *
 * @package App\Models
 */
class Conversation extends Model
{
	protected $table = 'Conversations';

	protected $fillable = [
		'conversation_id'
	];

	public function messages()
	{
		return $this->hasMany(Message::class, 'conversationsId', 'conversation_id');
	}

	public function participants()
	{
		return $this->belongsToMany(Participant::class, '_ConversationsToParticipants', 'A', 'B');
	}
}
