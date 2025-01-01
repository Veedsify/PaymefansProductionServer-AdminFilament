<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property string $product_id
 * @property int $user_id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property int $category_id
 * @property int $instock
 * 
 * @property ProductCategory $product_category
 * @property Collection|ProductImage[] $product_images
 * @property Collection|ProductSizePivot[] $product_size_pivots
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'Product';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'price' => 'float',
		'category_id' => 'int',
		'instock' => 'int'
	];

	protected $fillable = [
		'product_id',
		'user_id',
		'name',
		'description',
		'price',
		'category_id',
		'instock'
	];

	public function product_category()
	{
		return $this->belongsTo(ProductCategory::class, 'category_id');
	}

	public function product_images()
	{
		return $this->hasMany(ProductImage::class, 'product_id');
	}

	public function product_size_pivots()
	{
		return $this->hasMany(ProductSizePivot::class, 'product_id');
	}
}
