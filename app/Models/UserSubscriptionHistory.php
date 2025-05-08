<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSubscriptionHistory
 * 
 * @property int $id
 * @property string $subscription_id
 * @property int $user_id
 * @property int $model_id
 * @property string $subscription
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class UserSubscriptionHistory extends Model
{
	protected $table = 'UserSubscriptionHistory';

	protected $casts = [
		'user_id' => 'int',
		'model_id' => 'int'
	];

	protected $fillable = [
		'subscription_id',
		'user_id',
		'model_id',
		'subscription'
	];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

	public function model()
	{
		return $this->belongsTo(User::class, 'model_id');
	}


}
