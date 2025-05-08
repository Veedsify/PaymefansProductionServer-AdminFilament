<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductSizePivot
 * 
 * @property int $id
 * @property int $product_id
 * @property int $size_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Product $product
 * @property ProductSize $product_size
 *
 * @package App\Models
 */
class ProductSizePivot extends Model
{
	protected $table = 'ProductSizePivot';

	protected $casts = [
		'product_id' => 'int',
		'size_id' => 'int'
	];

	// Enable timestamps for the pivot table
	public $timestamps = true;

	// Define fillable attributes if needed
	protected $fillable = ['product_id', 'size_id', 'created_at', 'updated_at'];
}
