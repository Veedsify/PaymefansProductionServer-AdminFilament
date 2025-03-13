<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportComment
 * 
 * @property int $id
 * @property string $report_id
 * @property int $user_id
 * @property int $comment_id
 * @property string $report_type
 * @property string $report
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property PostComment $post_comment
 * @property User $user
 *
 * @package App\Models
 */
class ReportComment extends Model
{
	protected $table = 'ReportComment';

	protected $casts = [
		'user_id' => 'int',
		'comment_id' => 'int'
	];

	protected $fillable = [
		'report_id',
		'user_id',
		'comment_id',
		'report_type',
		'report'
	];

	public function post_comment()
	{
		return $this->belongsTo(PostComment::class, 'comment_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
