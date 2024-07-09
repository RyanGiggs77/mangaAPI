<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    use HasFactory;

    protected $fillable=['title','author','description','image','slug'];

    public function chapters() 
    {
        return $this->hasMany(Chapter::class);
    }
}
