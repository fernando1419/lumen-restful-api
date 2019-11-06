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
		$route = $request->route(); // route information
		$param = reset($route[2]); // route parameters are the 3rd item in the route info array

		if ($param && ! is_numeric($param)) {
			$apiController = new \App\Http\Controllers\ApiController();
			return $apiController->respondBadRequest("Bad request. The parameter '{$param}' must be numeric.");
		}

		return $next($request);
	}
}
