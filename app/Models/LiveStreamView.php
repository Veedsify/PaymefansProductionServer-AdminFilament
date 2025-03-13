<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LiveStreamView
 * 
 * @property int $id
 * @property int $user_id
 * @property int $live_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property LiveStream $live_stream
 * @property User $user
 *
 * @package App\Models
 */
class LiveStreamView extends Model
{
	protected $table = 'LiveStreamView';

	protected $casts = [
		'user_id' => 'int',
		'live_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'live_id'
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
