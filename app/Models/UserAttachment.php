<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAttachment
 * 
 * @property int $id
 * @property string $path
 * @property string $type
 * @property string $name
 * @property int $size
 * @property string $extension
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $user_id
 * 
 * @property User $user
 *
 * @package App\Models
 */
class UserAttachment extends Model
{
	protected $table = 'UserAttachments';

	protected $casts = [
		'size' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'path',
		'type',
		'name',
		'size',
		'extension',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
