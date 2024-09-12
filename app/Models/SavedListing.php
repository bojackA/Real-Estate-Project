<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedListing extends Model
{
    protected $table = 'image_post';
    protected $fillable = ['post_id','user_id'];
    use HasFactory;
    public function post()
{
    return $this->belongsTo(Post::class);
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
