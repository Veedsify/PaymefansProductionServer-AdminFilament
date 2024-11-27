<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Post
 * 
 * @property int $id
 * @property string $post_id
 * @property bool $was_repost
 * @property string|null $repost_username
 * @property string|null $repost_id
 * @property int $user_id
 * @property string $content
 * @property array|null $media
 * @property string $post_status
 * @property string $post_audience
 * @property bool $post_is_visible
 * @property int $post_likes
 * @property int $post_comments
 * @property int $post_reposts
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Collection|PostShared[] $post_shareds
 * @property Collection|ReportPost[] $report_posts
 * @property Collection|UserMedia[] $user_media
 * @property Collection|UserRepost[] $user_reposts
 *
 * @package App\Models
 */
class Post extends Model
{
	protected $table = 'Post';

	protected $casts = [
		'was_repost' => 'bool',
		'user_id' => 'int',
		'media' => 'json',
		'post_is_visible' => 'bool',
		'post_likes' => 'int',
		'post_comments' => 'int',
		'post_reposts' => 'int'
	];

	protected $fillable = [
		'post_id',
		'was_repost',
		'repost_username',
		'repost_id',
		'user_id',
		'content',
		'media',
		'post_status',
		'post_audience',
		'post_is_visible',
		'post_likes',
		'post_comments',
		'post_reposts'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function post_comments()
	{
		return $this->hasMany(PostComment::class, 'post_id');
	}

	public function post_likes()
	{
		return $this->hasMany(PostLike::class, 'post_id');
	}

	public function post_shareds()
	{
		return $this->hasMany(PostShared::class, 'post_id');
	}

	public function report_posts()
	{
		return $this->hasMany(ReportPost::class, 'post_id');
	}

	public function user_media()
	{
		return $this->hasMany(UserMedia::class, 'post_id');
	}

	public function user_reposts()
	{
		return $this->hasMany(UserRepost::class, 'post_id');
	}
}
