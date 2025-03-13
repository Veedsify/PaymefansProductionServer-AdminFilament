<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HelpArticle
 * 
 * @property int $id
 * @property string $article_id
 * @property int $category_id
 * @property string $title
 * @property string $content
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property HelpCategory $help_category
 *
 * @package App\Models
 */
class HelpArticle extends Model
{
	protected $table = 'HelpArticles';

	protected $casts = [
		'category_id' => 'int'
	];

	protected $fillable = [
		'article_id',
		'category_id',
		'title',
		'content'
	];

	public function help_category()
	{
		return $this->belongsTo(HelpCategory::class, 'category_id');
	}
}
