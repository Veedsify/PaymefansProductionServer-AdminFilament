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
 * @property string|null $captionStyle
 * @property string|null $story_content
 * @property string $url
 * @property int|null $duration
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property UserStory $user_story
 *
 * @package App\Models
 */
class StoryMedia extends Model
{
	protected $table = 'StoryMedia';

	protected $casts = [
		'captionStyle' => 'binary',
		'duration' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'media_id',
		'media_type',
		'filename',
		'captionStyle',
		'story_content',
		'url',
		'duration',
		'user_id'
	];

	public function user_story()
	{
		return $this->belongsTo(UserStory::class, 'user_id');
	}
}
