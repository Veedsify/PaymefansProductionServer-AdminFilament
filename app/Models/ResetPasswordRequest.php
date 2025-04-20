<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ResetPasswordRequest
 * 
 * @property int $id
 * @property int $user_id
 * @property string $password
 * @property string $reset_code
 * @property bool $completed
 * @property Carbon $expires_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class ResetPasswordRequest extends Model
{
	protected $table = 'ResetPasswordRequests';

	protected $casts = [
		'user_id' => 'int',
		'completed' => 'bool',
		'expires_at' => 'datetime'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'user_id',
		'password',
		'reset_code',
		'completed',
		'expires_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
