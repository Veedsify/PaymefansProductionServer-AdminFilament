<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WishList
 * 
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Product $product
 *
 * @package App\Models
 */
class WishList extends Model
{
	protected $table = 'WishList';

	protected $casts = [
		'user_id' => 'int',
		'product_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'product_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id');
	}
}
