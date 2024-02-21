<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class response_answers extends Model
{
    use HasFactory;
    protected $fillable = [
        'survey_id', 
        'user_id',
        'user_type',
        'response',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
    public function responseAnswers()
{
    return $this->hasMany(answers::class, 'response_id', 'id');
}
}
