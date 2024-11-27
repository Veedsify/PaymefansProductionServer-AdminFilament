<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 * 
 * @property int $id
 * @property string $notification_id
 * @property int $user_id
 * @property string $message
 * @property string $action
 * @property string $url
 * @property bool $read
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Notification extends Model
{
	protected $table = 'Notifications';

	protected $casts = [
		'user_id' => 'int',
		'read' => 'bool'
	];

	protected $fillable = [
		'notification_id',
		'user_id',
		'message',
		'action',
		'url',
		'read'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
