<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LoginHistory
 * 
 * @property int $id
 * @property int $user_id
 * @property string $ip_address
 * @property string $device
 * @property string $city
 * @property string $state
 * @property string $capital
 * @property string $country
 * @property string $countryCode
 * @property string $continent
 * @property int $longitude
 * @property int $latitude
 * @property string $location
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class LoginHistory extends Model
{
	protected $table = 'LoginHistory';

	protected $casts = [
		'user_id' => 'int',
		'longitude' => 'int',
		'latitude' => 'int'
	];

	protected $fillable = [
		'user_id',
		'ip_address',
		'device',
		'city',
		'state',
		'capital',
		'country',
		'countryCode',
		'continent',
		'longitude',
		'latitude',
		'location'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
