<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StoryMedia
 * 
 * @property int $id
 * @property string $media_id
 * @property string $media_type
 * @property string $filename
 * @property array|null $captionStyle
 * @property string|null $story_content
 * @property string $url
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $duration
 * 
 * @property UserStory $user_story
 *
 * @package App\Models
 */
class StoryMedia extends Model
{
	protected $table = 'StoryMedia';

	protected $casts = [
		'captionStyle' => 'json',
		'user_id' => 'int',
		'duration' => 'int'
	];

	protected $fillable = [
		'media_id',
		'media_type',
		'filename',
		'captionStyle',
		'story_content',
		'url',
		'user_id',
		'duration'
	];

	public function user_story()
	{
		return $this->belongsTo(UserStory::class, 'user_id');
	}
}
