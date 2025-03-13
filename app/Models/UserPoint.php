<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPoint
 * 
 * @property int $id
 * @property int $user_id
 * @property int $points
 * @property float $conversion_rate
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Collection|UserPointsPurchase[] $user_points_purchases
 *
 * @package App\Models
 */
class UserPoint extends Model
{
	protected $table = 'UserPoints';

	protected $casts = [
		'user_id' => 'int',
		'points' => 'int',
		'conversion_rate' => 'float'
	];

	protected $fillable = [
		'user_id',
		'points',
		'conversion_rate'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function user_points_purchases()
	{
		return $this->hasMany(UserPointsPurchase::class, 'userPointsId');
	}
}
