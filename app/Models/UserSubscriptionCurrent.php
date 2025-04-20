<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSubscriptionCurrent
 * 
 * @property int $id
 * @property string $subscription_id
 * @property int $user_id
 * @property int $model_id
 * @property string $subscription
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $userId
 * 
 * @property User $user
 *
 * @package App\Models
 */
class UserSubscriptionCurrent extends Model
{
	protected $table = 'UserSubscriptionCurrent';

	protected $casts = [
		'user_id' => 'int',
		'model_id' => 'int',
		'userId' => 'int'
	];

	protected $fillable = [
		'subscription_id',
		'user_id',
		'model_id',
		'subscription',
		'userId'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'model_id');
	}
}
