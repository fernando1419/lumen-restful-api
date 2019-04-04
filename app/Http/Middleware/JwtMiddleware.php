<?php namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;
use Firebase\JWT\ExpiredException;
use App\Http\Controllers\ApiController;

class JwtMiddleware
{
    /**
     * $apiController
     *
     * @var undefined
     */
    protected $apiController;
    
    /**
     * __construct
     *
     * @param ApiController $apiController
     * @return void
     */
    public function __construct(ApiController $apiController)
    {
        $this->apiController = $apiController;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $this->getToken($request);
     
        if (! $token) {
            return $this->apiController->respondUnauthorized('Token not provided.');
        }
           
        try {
            $token       = ( Str::contains($token, 'Bearer ') ) ? str_replace('Bearer ', '', $token) : $token;
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return $this->apiController->respondBadRequest( 'Provided token has expired.' );
        } catch(Exception $e) {
            return $this->apiController->respondBadRequest( 'An error occurred while decoding token.' );
        }
        
        // dd($credentials);
        $request->auth = $credentials->sub; // User instance

        return $next($request);
    }

    /**
     * getToken from client request
     *
     * @param Request $request
     * @return void
     */
    private function getToken($request)
    {
        $token = $request->header('authorization'); // Bearer token.

        if (! $token) // If not bearer token, then get token from header and if not present from the body of the form request.
        {
            $token = ($request->header('token')) ? $request->header('token') : $request->get('token');
        }

        return $token;
    }
    
}
