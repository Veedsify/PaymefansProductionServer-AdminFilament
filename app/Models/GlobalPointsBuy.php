<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GlobalPointsBuy
 * 
 * @property int $id
 * @property string $points_buy_id
 * @property int $points
 * @property float $amount
 * @property float $conversion_rate
 * @property string $currency
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class GlobalPointsBuy extends Model
{
	protected $table = 'GlobalPointsBuy';

	protected $casts = [
		'points' => 'int',
		'amount' => 'float',
		'conversion_rate' => 'float'
	];

	protected $fillable = [
		'points_buy_id',
		'points',
		'amount',
		'conversion_rate',
		'currency'
	];
}
