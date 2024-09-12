<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $fillable = ['post_id', 'image','image2','image3','image4'];
   
    

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
