<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportLive
 * 
 * @property int $id
 * @property string $report_id
 * @property int $user_id
 * @property int $live_id
 * @property string $report_type
 * @property string $report
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property LiveStream $live_stream
 *
 * @package App\Models
 */
class ReportLive extends Model
{
	protected $table = 'ReportLive';

	protected $casts = [
		'user_id' => 'int',
		'live_id' => 'int'
	];

	protected $fillable = [
		'report_id',
		'user_id',
		'live_id',
		'report_type',
		'report'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function live_stream()
	{
		return $this->belongsTo(LiveStream::class, 'live_id');
	}
}
