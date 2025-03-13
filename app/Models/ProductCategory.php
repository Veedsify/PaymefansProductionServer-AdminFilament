<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductCategory
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon $created_at
 * 
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class ProductCategory extends Model
{
	protected $table = 'ProductCategory';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'description'
	];

	public function products()
	{
		return $this->hasMany(Product::class, 'category_id');
	}
}
