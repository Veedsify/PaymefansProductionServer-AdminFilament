<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscriber
 * 
 * @property int $id
 * @property string $sub_id
 * @property int $user_id
 * @property int $subscriber_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Subscriber extends Model
{
	protected $table = 'Subscribers';

	protected $casts = [
		'user_id' => 'int',
		'subscriber_id' => 'int'
	];

	protected $fillable = [
		'sub_id',
		'user_id',
		'subscriber_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
