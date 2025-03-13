<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Model
 * 
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property int $user_id
 * @property string $gender
 * @property Carbon $dob
 * @property string $country
 * @property bool $hookup
 * @property string|null $verification_video
 * @property string|null $verification_image
 * @property bool $verification_status
 * @property string $verification_state
 * @property string|null $token
 * @property Carbon $created_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Model extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'Model';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'dob' => 'datetime',
		'hookup' => 'bool',
		'verification_status' => 'bool'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'firstname',
		'lastname',
		'user_id',
		'gender',
		'dob',
		'country',
		'hookup',
		'verification_video',
		'verification_image',
		'verification_status',
		'verification_state',
		'token'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
