<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PointConversionRate
 * 
 * @property int $id
 * @property int|null $amount
 * @property int|null $points
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class PointConversionRate extends Model
{
	protected $table = 'PointConversionRate';
	public $timestamps = false;

	protected $casts = [
		'amount' => 'int',
		'points' => 'int'
	];

	protected $fillable = [
		'amount',
		'points'
	];

	public function users()
	{
		return $this->belongsToMany(User::class, 'PointConversionRateUsers', 'pointConversionRateId')
					->withPivot('id');
	}
}
