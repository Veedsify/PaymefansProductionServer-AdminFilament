<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportMessage
 * 
 * @property int $id
 * @property string $report_id
 * @property int $user_id
 * @property int $message_id
 * @property string $report_type
 * @property string $report
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Message $message
 * @property User $user
 *
 * @package App\Models
 */
class ReportMessage extends Model
{
	protected $table = 'ReportMessage';

	protected $casts = [
		'user_id' => 'int',
		'message_id' => 'int'
	];

	protected $fillable = [
		'report_id',
		'user_id',
		'message_id',
		'report_type',
		'report'
	];

	public function message()
	{
		return $this->belongsTo(Message::class, 'message_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
