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

	public function product_size_pivots()
	{
		return $this->hasMany(ProductSizePivot::class, 'size_id');
	}
}
