<?php namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    protected $request;

    /**
     * __construct
     *
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create a new token.
     *
     * @param  App\User $user
     * @return string
     */
    protected function createJWT(User $user)
    {
        $payload = [
            'iss' => "api-lumen-jwt", // Issuer of the token
            'sub' => $user, // Subject of the token, holds a user instance.
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * 60 // Token Expiration time (60 minutes).
        ];

        return JWT::encode($payload, env('JWT_SECRET')); // `JWT_SECRET` is used for encoding and decoding the token. 
    }

    /**
     * login
     *
     * @return void
     */
    public function login()
    {
        $validator = Validator::make($this->request->all(), User::$rules);

        if ( $validator->fails() ) 
        {
            return $this->respondUnprocessableEntity($validator->errors());
        }
                
        $loginAttempt = User::authenticateByEmailAndPassword($this->request->get('email'), $this->request->get('password'));

        if ( $loginAttempt instanceof User )
        {
            return $this->respond( ['token' => $this->createJWT($loginAttempt)] );   
        }

        return $this->respondUnauthorized();
    }

}
