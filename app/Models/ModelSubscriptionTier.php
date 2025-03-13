<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelSubscriptionTier
 * 
 * @property int $id
 * @property int $subscription_id
 * @property string $tier_name
 * @property float $tier_price
 * @property string $tier_description
 * @property string $tier_duration
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property ModelSubscriptionPack $model_subscription_pack
 *
 * @package App\Models
 */
class ModelSubscriptionTier extends Model
{
	protected $table = 'ModelSubscriptionTier';

	protected $casts = [
		'subscription_id' => 'int',
		'tier_price' => 'float'
	];

	protected $fillable = [
		'subscription_id',
		'tier_name',
		'tier_price',
		'tier_description',
		'tier_duration'
	];

	public function model_subscription_pack()
	{
		return $this->belongsTo(ModelSubscriptionPack::class, 'subscription_id');
	}
}
