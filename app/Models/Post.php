<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;
    use HasFactory;

    public $timestamps = true;
    protected $fillable = ['title', 'body', 'user_id','updated_by'];
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'body' => $this->body
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class,'post_id','id'  );
    }
}
