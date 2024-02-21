<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class questions extends Model
{
    use HasFactory;
    protected $table = 'questions';
    protected $fillable = [
        'survey_id', 
        'question_num',
        'title',
        'type',
    ];
    public function survey() {
        return $this->belongsTo(Surveys::class, 'survey_id', 'id');
    }
    public function options()
    {
        return $this->hasMany(question_options::class, 'question_id', 'id');
    }
}
