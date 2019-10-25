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
	 * Validation rules for Book Model
	 *
	 * @return void
	 */
	protected static function rules()
	{
		return [
			'title'     => 'required|min:3',
			'author_id' => 'required|numeric'
		];
	}
}
