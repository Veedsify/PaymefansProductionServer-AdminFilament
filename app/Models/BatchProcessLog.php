<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BatchProcessLog
 * 
 * @property int $id
 * @property string $job_id
 * @property string $job_name
 * @property string $job_data
 *
 * @package App\Models
 */
class BatchProcessLog extends Model
{
	protected $table = 'BatchProcessLogs';
	public $timestamps = false;

	protected $fillable = [
		'job_id',
		'job_name',
		'job_data'
	];
}
