<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserWithdrawalBankAccount
 * 
 * @property int $id
 * @property int $user_id
 * @property string $bank_account_id
 * @property string $bank_name
 * @property string $account_name
 * @property string $account_number
 * @property string $routing_number
 * @property string $bank_country
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class UserWithdrawalBankAccount extends Model
{
	protected $table = 'UserWithdrawalBankAccount';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'bank_account_id',
		'bank_name',
		'account_name',
		'account_number',
		'routing_number',
		'bank_country'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
