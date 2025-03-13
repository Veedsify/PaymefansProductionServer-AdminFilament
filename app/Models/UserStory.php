<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserStory
 * 
 * @property int $id
 * @property string $story_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Collection|StoryMedia[] $story_media
 *
 * @package App\Models
 */
class UserStory extends Model
{
	protected $table = 'UserStory';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'story_id',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function story_media()
	{
		return $this->hasMany(StoryMedia::class, 'user_id');
	}
}
