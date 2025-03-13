<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSubscriptionHistory
 * 
 * @property int $id
 * @property string $subscription_id
 * @property string $user_id
 * @property string $subscription
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $userSubscriptionCurrentId
 * 
 * @property UserSubscriptionCurrent|null $user_subscription_current
 *
 * @package App\Models
 */
class UserSubscriptionHistory extends Model
{
	protected $table = 'UserSubscriptionHistory';

	protected $casts = [
		'userSubscriptionCurrentId' => 'int'
	];

	protected $fillable = [
		'subscription_id',
		'user_id',
		'subscription',
		'userSubscriptionCurrentId'
	];

	public function user_subscription_current()
	{
		return $this->belongsTo(UserSubscriptionCurrent::class, 'userSubscriptionCurrentId');
	}
}
