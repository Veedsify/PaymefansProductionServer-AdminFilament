<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPointsPurchase
 * 
 * @property int $id
 * @property string $purchase_id
 * @property int $user_id
 * @property int $points
 * @property float $amount
 * @property bool $success
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $userPointsId
 * 
 * @property UserPoint|null $user_point
 *
 * @package App\Models
 */
class UserPointsPurchase extends Model
{
	protected $table = 'UserPointsPurchase';

	protected $casts = [
		'user_id' => 'int',
		'points' => 'int',
		'amount' => 'float',
		'success' => 'bool',
		'userPointsId' => 'int'
	];

	protected $fillable = [
		'purchase_id',
		'user_id',
		'points',
		'amount',
		'success',
		'userPointsId'
	];

	public function user_point()
	{
		return $this->belongsTo(UserPoint::class, 'userPointsId');
	}
}
