<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class create_format extends Model
{
    use HasFactory;
    protected $table = 'create_format';
    public function surveys()
{
    return $this->hasMany(surveys::class, 'invite_id');
}
}


