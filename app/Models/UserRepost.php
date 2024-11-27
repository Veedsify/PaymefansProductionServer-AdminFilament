<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserRepost
 * 
 * @property int $id
 * @property string $repost_id
 * @property int $user_id
 * @property int $post_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Post $post
 * @property User $user
 *
 * @package App\Models
 */
class UserRepost extends Model
{
	protected $table = 'UserRepost';

	protected $casts = [
		'user_id' => 'int',
		'post_id' => 'int'
	];

	protected $fillable = [
		'repost_id',
		'user_id',
		'post_id'
	];

	public function post()
	{
		return $this->belongsTo(Post::class, 'post_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
