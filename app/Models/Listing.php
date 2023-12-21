<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;
    protected $fillable =['title','description','user_id'];

    public function image(){
        return $this->morphMany(Image::class,'imagable');
    }


}
