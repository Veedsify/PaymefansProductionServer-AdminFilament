<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Group
 * 
 * @property int $id
 * @property string $group_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|BlockedGroupParticipant[] $blocked_group_participants
 * @property Collection|GroupParticipant[] $group_participants
 * @property GroupSetting $group_setting
 *
 * @package App\Models
 */
class Groups extends Model
{
	protected $table = 'Groups';

	protected $fillable = [
		'group_id'
	];

	public function blocked_group_participants()
	{
		return $this->hasMany(BlockedGroupParticipant::class, 'group_id');
	}

	public function group_participants()
	{
		return $this->hasMany(GroupParticipant::class, 'group_id');
	}

	public function group_setting()
	{
		return $this->hasOne(GroupSetting::class, 'group_id');
	}
}
