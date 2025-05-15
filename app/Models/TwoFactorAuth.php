<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TwoFactorAuth
 * 
 * @property int $id
 * @property int $user_id
 * @property int $code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class TwoFactorAuth extends Model
{
	protected $table = 'TwoFactorAuth';

	protected $casts = [
		'user_id' => 'int',
		'code' => 'int'
	];

	protected $fillable = [
		'user_id',
		'code'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
