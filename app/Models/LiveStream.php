<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LiveStream
 * 
 * @property int $id
 * @property string $user_id
 * @property string $username
 * @property string $stream_id
 * @property string $stream_token
 * @property string $user_stream_id
 * @property string $title
 * @property string $stream_call_id
 * @property string $stream_status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Collection|LiveStreamComment[] $live_stream_comments
 * @property Collection|LiveStreamLike[] $live_stream_likes
 * @property Collection|LiveStreamParticipant[] $live_stream_participants
 * @property Collection|ReportLive[] $report_lives
 *
 * @package App\Models
 */
class LiveStream extends Model
{
	protected $table = 'LiveStream';

	protected $hidden = [
		'stream_token'
	];

	protected $fillable = [
		'user_id',
		'username',
		'stream_id',
		'stream_token',
		'user_stream_id',
		'title',
		'stream_call_id',
		'stream_status'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'user_id');
	}

	public function live_stream_comments()
	{
		return $this->hasMany(LiveStreamComment::class, 'live_id');
	}

	public function live_stream_likes()
	{
		return $this->hasMany(LiveStreamLike::class, 'live_id');
	}

	public function live_stream_participants()
	{
		return $this->hasMany(LiveStreamParticipant::class, 'liveStreamId');
	}

	public function report_lives()
	{
		return $this->hasMany(ReportLive::class, 'live_id');
	}
}
