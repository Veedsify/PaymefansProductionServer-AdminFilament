<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * 
 * @property int $id
 * @property int $user_id
 * @property float $price_per_message
 * @property bool $subscription_active
 * @property bool $enable_free_message
 * @property float $subscription_price
 * @property string $subscription_type
 * @property string $subscription_duration
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Setting extends Model
{
	protected $table = 'Settings';

	protected $casts = [
		'user_id' => 'int',
		'price_per_message' => 'float',
		'subscription_active' => 'bool',
		'enable_free_message' => 'bool',
		'subscription_price' => 'float'
	];

	protected $fillable = [
		'user_id',
		'price_per_message',
		'subscription_active',
		'enable_free_message',
		'subscription_price',
		'subscription_type',
		'subscription_duration'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
