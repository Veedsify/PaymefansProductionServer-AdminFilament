<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * 
 * @property int $id
 * @property string $order_id
 * @property string $transaction_id
 * @property int $user_id
 * @property float $total_amount
 * @property bool $paid_status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'Order';

	protected $casts = [
		'user_id' => 'int',
		'total_amount' => 'float',
		'paid_status' => 'bool'
	];

	protected $fillable = [
		'order_id',
		'transaction_id',
		'user_id',
		'total_amount',
		'paid_status'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function products()
	{
		return $this->belongsToMany(Product::class, 'OrderProduct')
					->withPivot('id', 'quantity', 'status')
					->withTimestamps();
	}
}
