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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Post $post
 * @property User $user
 * @property Collection|PostCommentAttachment[] $post_comment_attachments
 * @property Collection|ReportComment[] $report_comments
 *
 * @package App\Models
 */
class PostComment extends Model
{
	protected $table = 'PostComment';

	protected $casts = [
		'user_id' => 'int',
		'post_id' => 'int'
	];

	protected $fillable = [
		'comment_id',
		'user_id',
		'post_id',
		'comment'
	];

	public function post()
	{
		return $this->belongsTo(Post::class, 'post_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function post_comment_attachments()
	{
		return $this->hasMany(PostCommentAttachment::class, 'comment_id');
	}

	public function report_comments()
	{
		return $this->hasMany(ReportComment::class, 'comment_id');
	}
}
