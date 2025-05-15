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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class WithdrawalRequest extends Model
{
	protected $table = 'WithdrawalRequest';

	protected $casts = [
		'user_id' => 'int',
		'amount' => 'int',
	];

	protected $fillable = [
		'user_id',
		'amount',
		'recipient_code',
		'reason',
		'status'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
