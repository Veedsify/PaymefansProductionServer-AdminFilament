<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LiveStreamComment
 * 
 * @property int $id
 * @property string $live_comment_id
 * @property int $user_id
 * @property int $live_id
 * @property string $comment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property LiveStream $live_stream
 * @property User $user
 *
 * @package App\Models
 */
class LiveStreamComment extends Model
{
	protected $table = 'LiveStreamComment';

	protected $casts = [
		'user_id' => 'int',
		'live_id' => 'int'
	];

	protected $fillable = [
		'live_comment_id',
		'user_id',
		'live_id',
		'comment'
	];

	public function live_stream()
	{
		return $this->belongsTo(LiveStream::class, 'live_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
