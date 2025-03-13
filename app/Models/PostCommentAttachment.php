<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PostCommentAttachment
 * 
 * @property int $id
 * @property int $comment_id
 * @property string $path
 * @property string $type
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property PostComment $post_comment
 *
 * @package App\Models
 */
class PostCommentAttachment extends Model
{
	protected $table = 'PostCommentAttachments';

	protected $casts = [
		'comment_id' => 'int'
	];

	protected $fillable = [
		'comment_id',
		'path',
		'type',
		'name'
	];

	public function post_comment()
	{
		return $this->belongsTo(PostComment::class, 'comment_id');
	}
}
