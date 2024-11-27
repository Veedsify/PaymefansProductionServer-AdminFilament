<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportUser
 * 
 * @property int $id
 * @property string $report_id
 * @property int $user_id
 * @property string $reported_id
 * @property string $report_type
 * @property string $report
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class ReportUser extends Model
{
	protected $table = 'ReportUser';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'report_id',
		'user_id',
		'reported_id',
		'report_type',
		'report'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
