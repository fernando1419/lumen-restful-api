<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'isbn', 'author_id'];

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

    // protected static function rules($ignoreId = null)
    // {
    //     return [
    //         'name' => 'required',
    //         'email' => 'bail|required|email|unique:authors,email' . (!is_null($ignoreId) ? ",{$ignoreId}" : null)
    //     ];
    // }
}
