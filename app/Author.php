<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'github', 'twitter', 'location', 'last_article_published'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Validation rules for Author Model.
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'email' => 'required|email'
    ];

}
