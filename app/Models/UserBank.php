<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserBank
 * 
 * @property int $id
 * @property int $user_id
 * @property string $bank_id
 * @property string $bank_name
 * @property string $account_name
 * @property string $account_number
 * @property string|null $routing_number
 * @property string|null $swift_code
 * @property string $bank_country
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class UserBank extends Model
{
	protected $table = 'UserBanks';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'bank_id',
		'bank_name',
		'account_name',
		'account_number',
		'routing_number',
		'swift_code',
		'bank_country'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
