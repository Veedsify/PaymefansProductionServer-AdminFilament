<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PostCommentLike
 * 
 * @property int $id
 * @property string $like_id
 * @property int $user_id
 * @property int $comment_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property PostComment $post_comment
 *
 * @package App\Models
 */
class PostCommentLike extends Model
{
	protected $table = 'PostCommentLikes';

	protected $casts = [
		'user_id' => 'int',
		'comment_id' => 'int'
	];

	protected $fillable = [
		'like_id',
		'user_id',
		'comment_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function post_comment()
	{
		return $this->belongsTo(PostComment::class, 'comment_id');
	}
}
