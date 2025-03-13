<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PostShared
 * 
 * @property int $id
 * @property string $shared_id
 * @property int|null $user_id
 * @property int $post_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Post $post
 * @property User|null $user
 *
 * @package App\Models
 */
class PostShared extends Model
{
	protected $table = 'PostShared';

	protected $casts = [
		'user_id' => 'int',
		'post_id' => 'int'
	];

	protected $fillable = [
		'shared_id',
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
