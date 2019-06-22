<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user() {
        // https://laravel.com/api/5.4/Illuminate/Database/Eloquent/Model.html
        // Define an inverse one-to-one or many relationship.
        // A Post belongs to a user (and)
        // A user may have one to many posts
        return $this->belongsTo('App\User');
    }
}
