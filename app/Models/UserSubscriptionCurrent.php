<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSubscriptionCurrent
 * 
 * @property int $id
 * @property string $subscription_id
 * @property int $user_id
 * @property string $subscription
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Collection|UserSubscriptionHistory[] $user_subscription_histories
 *
 * @package App\Models
 */
class UserSubscriptionCurrent extends Model
{
	protected $table = 'UserSubscriptionCurrent';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'subscription_id',
		'user_id',
		'subscription'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function user_subscription_histories()
	{
		return $this->hasMany(UserSubscriptionHistory::class, 'userSubscriptionCurrentId');
	}
}
