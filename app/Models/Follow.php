<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Follow
 * 
 * @property int $id
 * @property string $follow_id
 * @property int $user_id
 * @property int $follower_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Follow extends Model
{
	protected $table = 'Follow';

	protected $casts = [
		'user_id' => 'int',
		'follower_id' => 'int'
	];

	protected $fillable = [
		'follow_id',
		'user_id',
		'follower_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
