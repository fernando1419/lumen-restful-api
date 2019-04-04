<?php

namespace App\Http\Middleware;

use Closure;

class CheckRouteParam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $author_param = $request->route('author'); 
        
        if ( $author_param && ! is_numeric($author_param) )
        {
            $apiController = new \App\Http\Controllers\ApiController();
            return $apiController->respondBadRequest("Bad request. The parameter '{$author_param}' must be numeric.");
        }

        return $next($request);
    }
}
