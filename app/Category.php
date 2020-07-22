<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $with = ['categories'];

    function categories() {
        return $this->hasMany('App\Category');
    }

    public function parent() {
        return $this->belongsTo('App\Category', 'category_id');
    }
}
