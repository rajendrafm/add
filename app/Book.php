<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title','author_id','amount'];
    public function books()
    {
    	return $this->belongTo('App\Author');
    }
}
