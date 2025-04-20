<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserMedia
 * 
 * @property int $id
 * @property string $media_id
 * @property int $post_id
 * @property string $media_type
 * @property USER-DEFINED $media_state
 * @property string|null $duration
 * @property string $url
 * @property string $blur
 * @property string $poster
 * @property bool $locked
 * @property string $accessible_to
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Post $post
 *
 * @package App\Models
 */
class UserMedia extends Model
{
	protected $table = 'UserMedia';

	protected $casts = [
		'post_id' => 'int',
		'media_state' => 'USER-DEFINED',
		'locked' => 'bool'
	];

	protected $fillable = [
		'media_id',
		'post_id',
		'media_type',
		'media_state',
		'duration',
		'url',
		'blur',
		'poster',
		'locked',
		'accessible_to'
	];

	public function post()
	{
		return $this->belongsTo(Post::class, 'post_id');
	}
}
