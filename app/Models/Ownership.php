<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ownership extends Model
{
    use HasFactory;

    protected $table = 'ownership_images';
    protected $fillable = ['post_id', 'post_id','id_image','ownership_image'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
   
}
