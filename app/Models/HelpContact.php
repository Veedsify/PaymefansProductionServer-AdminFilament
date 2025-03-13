<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HelpContact
 * 
 * @property int $id
 * @property int $user_id
 * @property string $subject
 * @property string $message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class HelpContact extends Model
{
	protected $table = 'HelpContact';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'subject',
		'message'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
