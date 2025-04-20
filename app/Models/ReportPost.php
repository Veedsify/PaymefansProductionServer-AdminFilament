<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportPost
 * 
 * @property int $id
 * @property string $report_id
 * @property int $user_id
 * @property int $post_id
 * @property string $report_type
 * @property string $report
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Post $post
 *
 * @package App\Models
 */
class ReportPost extends Model
{
	protected $table = 'ReportPost';

	protected $casts = [
		'user_id' => 'int',
		'post_id' => 'int'
	];

	protected $fillable = [
		'report_id',
		'user_id',
		'post_id',
		'report_type',
		'report'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function post()
	{
		return $this->belongsTo(Post::class, 'post_id');
	}
}
