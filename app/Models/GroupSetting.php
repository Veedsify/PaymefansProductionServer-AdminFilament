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

	protected $casts = [
		'group_id' => 'int'
	];

	protected $fillable = [
		'group_id',
		'name',
		'description',
		'group_icon'
	];

	public function group()
	{
		return $this->belongsTo(Group::class, 'group_id');
	}
}
