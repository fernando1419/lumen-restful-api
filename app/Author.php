<?php namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Author extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'github', 'twitter', 'location', 'last_article_published', 'some_boolean'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Validation rules for Author Model
     *
     * @param mixed $ignoreId Id to Ignore on update requests.
     * @return void
     */
    protected static function rules($ignoreId = null) 
    {
        return [
            'name' => 'required',
            'email' => 'bail|required|email|unique:authors,email' . (!is_null($ignoreId) ? ",{$ignoreId}" : null)
        ];
    }     
}
