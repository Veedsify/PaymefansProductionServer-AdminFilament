<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConversationsToParticipant
 * 
 * @property int $A
 * @property int $B
 * 
 * @property Conversation $conversation
 * @property Participant $participant
 *
 * @package App\Models
 */
class ConversationsToParticipant extends Model
{
	protected $table = '_ConversationsToParticipants';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'A' => 'int',
		'B' => 'int'
	];

	public function conversation()
	{
		return $this->belongsTo(Conversation::class, 'A');
	}

	public function participant()
	{
		return $this->belongsTo(Participant::class, 'B');
	}
}
