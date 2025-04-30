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
 * @property string|null $content
 * @property string|null $media
 * @property USER-DEFINED $post_status
 * @property USER-DEFINED $post_audience
 * @property bool $post_is_visible
 * @property int $post_likes
 * @property int $post_comments
 * @property int $post_reposts
 * @property int $post_impressions
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property float|null $post_price
 * 
 * @property User $user
 * @property Collection|UserMedia[] $user_media
 * @property Collection|UserRepost[] $user_reposts
 * @property Collection|PostShared[] $post_shareds
 * @property Collection|ReportPost[] $report_posts
 *
 * @package App\Models
 */
class Post extends Model
{
	protected $table = 'Post';

	protected $casts = [
		'was_repost' => 'bool',
		'user_id' => 'int',
		'post_likes' => 'int',
		'post_comments' => 'int',
		'post_reposts' => 'int',
		'post_impressions' => 'int',
		'post_price' => 'float'
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
		'post_reposts',
		'post_impressions',
		'post_price'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function user_media()
	{
		return $this->hasMany(UserMedia::class, 'post_id');
	}

	public function user_reposts()
	{
		return $this->hasMany(UserRepost::class, 'post_id');
	}

	public function post_impressions()
	{
		return $this->hasMany(PostImpression::class, 'post_id');
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
}
