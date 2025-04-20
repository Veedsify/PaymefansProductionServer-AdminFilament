<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cart
 * 
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $quantity
 * @property int $size_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Product $product
 * @property ProductSize $product_size
 *
 * @package App\Models
 */
class Cart extends Model
{
	protected $table = 'Cart';

	protected $casts = [
		'user_id' => 'int',
		'product_id' => 'int',
		'quantity' => 'int',
		'size_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'product_id',
		'quantity',
		'size_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id');
	}

	public function product_size()
	{
		return $this->belongsTo(ProductSize::class, 'size_id');
	}
}
