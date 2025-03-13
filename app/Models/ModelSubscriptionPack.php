<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelSubscriptionPack
 * 
 * @property int $id
 * @property string $subscription_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $user_id
 * 
 * @property User $user
 * @property Collection|ModelSubscriptionTier[] $model_subscription_tiers
 *
 * @package App\Models
 */
class ModelSubscriptionPack extends Model
{
	protected $table = 'ModelSubscriptionPack';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'subscription_id',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function model_subscription_tiers()
	{
		return $this->hasMany(ModelSubscriptionTier::class, 'subscription_id');
	}
}
