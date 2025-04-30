<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PostComment
 * 
 * @property int $id
 * @property string $comment_id
 * @property int $user_id
 * @property int $post_id
 * @property string $comment
 * @property int $comment_impressions
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Post $post
 * @property Collection|PostCommentAttachment[] $post_comment_attachments
 * @property Collection|PostCommentLike[] $post_comment_likes
 * @property Collection|ReportComment[] $report_comments
 *
 * @package App\Models
 */
class PostComment extends Model
{
	protected $table = 'PostComment';

	protected $casts = [
		'user_id' => 'int',
		'post_id' => 'int',
		'comment_impressions' => 'int'
	];

	protected $fillable = [
		'comment_id',
		'user_id',
		'post_id',
		'comment',
		'comment_impressions'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function post()
	{
		return $this->belongsTo(Post::class, 'post_id');
	}

	public function comment_impressions()
	{
		return $this->hasMany(CommentImpression::class, 'comment_id');
	}

	public function post_comment_attachments()
	{
		return $this->hasMany(PostCommentAttachment::class, 'comment_id');
	}

	public function post_comment_likes()
	{
		return $this->hasMany(PostCommentLike::class, 'comment_id');
	}

	public function report_comments()
	{
		return $this->hasMany(ReportComment::class, 'comment_id');
	}
}
