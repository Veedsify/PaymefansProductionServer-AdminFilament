<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderProduct
 * 
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property USER-DEFINED $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Order $order
 * @property Product $product
 *
 * @package App\Models
 */
class OrderProduct extends Model
{
	protected $table = 'OrderProduct';

	protected $casts = [
		'order_id' => 'int',
		'product_id' => 'int',
		'quantity' => 'int',
		'status' => 'USER-DEFINED'
	];

	protected $fillable = [
		'order_id',
		'product_id',
		'quantity',
		'status'
	];

	public function order()
	{
		return $this->belongsTo(Order::class, 'order_id');
	}

	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id');
	}
}
