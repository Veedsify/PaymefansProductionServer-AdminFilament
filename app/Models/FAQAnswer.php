<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FAQAnswer
 * 
 * @property int $id
 * @property int $faq_id
 * @property string $answer
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property FAQ $f_a_q
 *
 * @package App\Models
 */
class FAQAnswer extends Model
{
	protected $table = 'FAQAnswers';

	protected $casts = [
		'faq_id' => 'int'
	];

	protected $fillable = [
		'faq_id',
		'answer'
	];

	public function f_a_q()
	{
		return $this->belongsTo(FAQ::class, 'faq_id');
	}
}
