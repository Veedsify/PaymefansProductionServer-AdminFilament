<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Participant
 * 
 * @property int $id
 * @property string $user_1
 * @property string $user_2
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Conversation[] $conversations
 *
 * @package App\Models
 */
class Participant extends Model
{
	protected $table = 'Participants';

	protected $fillable = [
		'user_1',
		'user_2'
	];

	public function conversations()
	{
		return $this->belongsToMany(Conversation::class, '_ConversationsToParticipants', 'B', 'A');
	}
}
