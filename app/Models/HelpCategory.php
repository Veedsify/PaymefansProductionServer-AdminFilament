<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HelpCategory
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon $created_at
 * 
 * @property Collection|HelpArticle[] $help_articles
 *
 * @package App\Models
 */
class HelpCategory extends Model
{
	protected $table = 'HelpCategory';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'description'
	];

	public function help_articles()
	{
		return $this->hasMany(HelpArticle::class, 'category_id');
	}
}
