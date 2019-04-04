<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Validation rules for User Model.
     *
     * @var array
     */
    public static $rules = [
        'email' => 'required|email',
        'password' => 'required'
    ];

    /**
     * authenticateByEmailAndPassword
     *
     * @param string $email
     * @param string $password
     * @return Instance of User or null. 
     */
    public static function authenticateByEmailAndPassword($email, $password)
    {
        $user = self::whereEmail($email)->first();
        
        if (! $user) 
        {
            return null;
        } 
        
        if (! app()->make('hash')->check($password, $user->password))
        {
            return null;
        }
        
        return $user;
    }
    
}
