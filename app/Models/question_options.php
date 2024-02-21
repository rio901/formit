<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question_options extends Model
{
    use HasFactory;
    protected $table = 'question_options';
    protected $fillable = [
        'question_id', 
        'label',
    ];

    public function options()
    {
        return $this->belongsTo(question_options::class, 'question_id', 'id');
    }
}
