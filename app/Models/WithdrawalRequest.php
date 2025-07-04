<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WithdrawalRequest
 * 
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property string $recipient_code
 * @property string $reason
 * @property USER-DEFINED $status
 * @property string $transfer_code
 * @property string $reference
 * @property array $paystack_response
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property UserBank $bankAccount
 *
 * @package App\Models
 */
class WithdrawalRequest extends Model
{
	protected $table = 'WithdrawalRequest';

	protected $casts = [
		'user_id' => 'int',
		'amount' => 'int',
		'paystack_response' => 'array',
	];

	protected $fillable = [
		'user_id',
		'amount',
		'recipient_code',
		'reason',
		'bank_account_id',
		'status',
		'transfer_code',
		'reference',
		'paystack_response'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function bankAccount()
	{
		return $this->belongsTo(UserBank::class, 'bank_account_id');
	}
}
