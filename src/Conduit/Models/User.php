<?php

namespace Conduit\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer        id
 * @property string         email
 * @property string         username
 * @property string         image
 * @property string         bio
 * @property string         token
 * @property string         password
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon update_at
 */
class User extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'token',
        'image',
        'bio',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    /********************
     *  Relationships
     ********************/

    /**
     * Create Many-To-Many followers relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'users_following',
            'user_id',
            'following_user_id')
            ->withTimestamps();
    }

    /**
     * Create following relationship. This user will follow the user with the provided id
     * @param $id
     *
     * @return self
     */
    public function follow($id)
    {
        $this->followers()->attach($id);

        return $this;
    }

    /**
     * Check if this user is following the user with the provided id
     * @param $id
     *
     * @return bool
     */
    public function isFollowing($id)
    {
        return $this->newBaseQueryBuilder()
            ->from('users_following')
            ->where('user_id', $this->id)
            ->where('following_user_id', $id)->exists();
    }

}