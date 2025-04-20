<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CommentImpression
 * 
 * @property int $id
 * @property int $comment_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $postId
 * 
 * @property PostComment $post_comment
 * @property User $user
 *
 * @package App\Models
 */
class CommentImpression extends Model
{
	protected $table = 'CommentImpression';

	protected $casts = [
		'comment_id' => 'int',
		'user_id' => 'int',
		'postId' => 'int'
	];

	protected $fillable = [
		'comment_id',
		'user_id',
		'postId'
	];

	public function post_comment()
	{
		return $this->belongsTo(PostComment::class, 'comment_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
