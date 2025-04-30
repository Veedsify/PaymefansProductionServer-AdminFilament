<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OuterPage extends Model
{
    protected $table = 'OuterPages';

    protected $fillable = [
        'title',
        'page_id',
        'slug',
        'content',
    ];
}
