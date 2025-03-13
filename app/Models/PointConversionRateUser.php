<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PointConversionRateUser
 * 
 * @property int $id
 * @property int $user_id
 * @property int|null $pointConversionRateId
 * 
 * @property PointConversionRate|null $point_conversion_rate
 * @property User $user
 *
 * @package App\Models
 */
class PointConversionRateUser extends Model
{
	protected $table = 'PointConversionRateUsers';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'pointConversionRateId' => 'int'
	];

	protected $fillable = [
		'user_id',
		'pointConversionRateId'
	];

	public function point_conversion_rate()
	{
		return $this->belongsTo(PointConversionRate::class, 'pointConversionRateId');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
