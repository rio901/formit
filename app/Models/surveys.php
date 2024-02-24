<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class surveys extends Model
{
    use HasFactory;
    protected $table = 'surveys';
    protected $fillable = [
        'name', 
        'free_comment', 
    ];
    public function createFormat()
{
    return $this->belongsTo(create_format::class, 'invite_id');
}

    public function questions() {
        return $this->hasMany(questions::class, 'survey_id', 'id');
    }
}
