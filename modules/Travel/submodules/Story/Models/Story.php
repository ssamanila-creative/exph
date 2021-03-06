<?php

namespace Story\Models;

use Category\Support\Traits\BelongsToCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pluma\Models\Model;
use Comment\Models\Comment;
use User\Support\Traits\BelongsToUser;

class Story extends Model
{
    use SoftDeletes, BelongsToUser, BelongsToCategory;

    protected $with = [];

    protected $fillable = ['code', 'title', 'feature', 'body', 'user_id', 'category_id'];

    protected $appends = ['author', 'modified', 'created'];

    protected $searchables = ['title', 'code', 'user_id', 'body', 'created_at', 'updated_at'];

    public function getAuthorAttribute()
    {
        return $this->user->displayname;
    }

    //
    public function story()
    {
        return $this->morphMany('Comment', 'commentable');
    }

    public function comments()
    {
        return $this->morphMany(\Comment\Models\Comment::class, 'commentable');
    }
}
