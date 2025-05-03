<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PlatformExchangeRate
 * 
 * @property int $id
 * @property int $rate
 * @property float $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class PlatformExchangeRate extends Model
{
	protected $table = 'PlatformExchangeRate';

	protected $casts = [
		'name' => 'string',
		'rate' => 'int',
		'value' => 'float'
	];

	protected $fillable = [
		'name',
		'rate',
		'value',
		'symbol'
	];
}
