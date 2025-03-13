<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LiveStreamParticipant
 * 
 * @property int $id
 * @property string $stream_id
 * @property string $host_id
 * @property string $participant_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $liveStreamId
 * 
 * @property LiveStream|null $live_stream
 *
 * @package App\Models
 */
class LiveStreamParticipant extends Model
{
	protected $table = 'LiveStreamParticipants';

	protected $casts = [
		'liveStreamId' => 'int'
	];

	protected $fillable = [
		'stream_id',
		'host_id',
		'participant_id',
		'liveStreamId'
	];

	public function live_stream()
	{
		return $this->belongsTo(LiveStream::class, 'liveStreamId');
	}
}
