<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserWallet
 * 
 * @property int $id
 * @property int $user_id
 * @property string $wallet_id
 * @property float $balance
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Collection|UserTransaction[] $user_transactions
 *
 * @package App\Models
 */
class UserWallet extends Model
{
	protected $table = 'UserWallet';

	protected $casts = [
		'user_id' => 'int',
		'balance' => 'float'
	];

	protected $fillable = [
		'user_id',
		'wallet_id',
		'balance'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function user_transactions()
	{
		return $this->hasMany(UserTransaction::class, 'wallet_id');
	}
}
