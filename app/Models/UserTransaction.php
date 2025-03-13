<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserTransaction
 * 
 * @property int $id
 * @property string $transaction_id
 * @property int $user_id
 * @property int $wallet_id
 * @property float $amount
 * @property string $transaction_message
 * @property string $transaction
 * @property Carbon $created_at
 * @property string $transaction_type
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property UserWallet $user_wallet
 *
 * @package App\Models
 */
class UserTransaction extends Model
{
	protected $table = 'UserTransaction';

	protected $casts = [
		'user_id' => 'int',
		'wallet_id' => 'int',
		'amount' => 'float'
	];

	protected $fillable = [
		'transaction_id',
		'user_id',
		'wallet_id',
		'amount',
		'transaction_message',
		'transaction',
		'transaction_type'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function user_wallet()
	{
		return $this->belongsTo(UserWallet::class, 'wallet_id');
	}
}
