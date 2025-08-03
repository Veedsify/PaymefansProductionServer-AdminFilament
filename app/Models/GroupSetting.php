<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GroupSetting
 * 
 * @property int $id
 * @property int $group_id
 * @property string $name
 * @property string $description
 * @property string $group_icon
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Group $group
 *
 * @package App\Models
 */
class GroupSetting extends Model
{
	protected $table = 'GroupSettings';

	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';

	protected $casts = [
		'groupId' => 'int'
	];

	protected $fillable = [
		'groupId',
		'allowFileSharing',
		'allowMediaSharing',
		'allowMemberInvites',
		'autoApproveJoinReqs',
		'created_at',
		'updated_at',
		'moderateMessages',
		'mutedUntil',
	];

	public function group()
	{
		return $this->belongsTo(Group::class, 'groupId');
	}
}
