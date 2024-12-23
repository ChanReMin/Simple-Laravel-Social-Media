<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'avatar_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class,'user_id');
    }
    public function feedPosts()
    {
        return Post::whereIn('user_id', function($query) {
            $query->select('followed_user')
                ->from('follows')
                ->where('user_id', $this->id);
        })
            ->where('user_id', '!=',$this->id)
            ->distinct()
            ->orderBy('updated_at','desc');
    }
    protected function avatar():Attribute{
        return Attribute::make(get: function($value){
            return $value ? '/storage/avatars/'.$value : 'https://via.placeholder.com/150';
        });
    }
    public function isFollowing(User $user)
    {
        return $this->followers()->where('followed_user', $user->id)->exists();
    }
    public function followers()
    {
        return $this->hasMany(Follow::class, 'followed_user');
    }
    public function followings()
    {
        return $this->hasMany(Follow::class, 'user_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class,'user_id');
    }
}
