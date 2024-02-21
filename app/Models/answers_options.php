<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class answers_options extends Model
{
    use HasFactory;
    protected $table = 'answer_option';
    protected $fillable = [
        'answer_id', 
        'option_id',
        'label',
    ];

    public function options()
    {
        return $this->hasMany(answers_options::class, 'answer_id', 'id');
    }
}
