<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class answers extends Model
{
    use HasFactory;
    protected $table = 'answers';

    protected $fillable = [
        'question_id',
        'text_option',
        'free_description',
        'email',
        'response_id',
    ];
    public function answer()
    {
        return $this->hasMany(answers::class, 'response_id', 'id');
    }
    public function options()
    {
        return $this->hasMany(answers_options::class, 'answer_id', 'id');
    }
}
