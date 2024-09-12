<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title','body','user_id','permission','bathrooms','rooms','location','address','size','type','price','id_image',
    'ownership_image','name','phone'];
    
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function ownimages()
    {
        return $this->hasMany(Ownership::class);
    }

   

    
    public function savedPosts()
    {
        return $this->hasMany(SavedListing::class);
    }
    use HasFactory;
   
    public function user()
{
    return $this->belongsTo(User::class);
}


}
