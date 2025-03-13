<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PostLike
 * 
 * @property int $id
 * @property int $like_id
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
class PostLike extends Model
{
	protected $table = 'PostLike';

	protected $casts = [
		'like_id' => 'int',
		'user_id' => 'int',
		'post_id' => 'int'
	];

	protected $fillable = [
		'like_id',
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
