<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductSize
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon $created_at
 * 
 * @property Collection|Cart[] $carts
 * @property Collection|ProductSizePivot[] $product_size_pivots
 *
 * @package App\Models
 */
class ProductSize extends Model
{
	protected $table = 'ProductSize';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'description'
	];

	public function carts()
	{
		return $this->hasMany(Cart::class, 'size_id');
	}

	public function products()
	{
		return $this->belongsToMany(Product::class, 'ProductSizePivot', 'size_id', 'product_id')->withTimestamps();
	}
}
