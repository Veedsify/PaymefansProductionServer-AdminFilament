<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PrismaMigration
 * 
 * @property string $id
 * @property string $checksum
 * @property Carbon|null $finished_at
 * @property string $migration_name
 * @property string|null $logs
 * @property Carbon|null $rolled_back_at
 * @property Carbon $started_at
 * @property int $applied_steps_count
 *
 * @package App\Models
 */
class PrismaMigration extends Model
{
	protected $table = '_prisma_migrations';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'finished_at' => 'datetime',
		'rolled_back_at' => 'datetime',
		'started_at' => 'datetime',
		'applied_steps_count' => 'int'
	];

	protected $fillable = [
		'checksum',
		'finished_at',
		'migration_name',
		'logs',
		'rolled_back_at',
		'started_at',
		'applied_steps_count'
	];
}
