<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FAQ
 * 
 * @property int $id
 * @property string $question
 * @property Carbon $created_at
 * 
 * @property Collection|FAQAnswer[] $f_a_q_answers
 *
 * @package App\Models
 */
class FAQ extends Model
{
	protected $table = 'FAQ';
	public $timestamps = false;

	protected $fillable = [
		'question'
	];

	public function f_a_q_answers()
	{
		return $this->hasMany(FAQAnswer::class, 'faq_id');
	}
}
