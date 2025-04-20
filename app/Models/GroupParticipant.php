<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GroupParticipant
 * 
 * @property int $id
 * @property int $user_id
 * @property int $group_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Group $group
 *
 * @package App\Models
 */
class GroupParticipant extends Model
{
	protected $table = 'GroupParticipants';

	protected $casts = [
		'user_id' => 'int',
		'group_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'group_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function group()
	{
		return $this->belongsTo(Group::class, 'group_id');
	}
}
