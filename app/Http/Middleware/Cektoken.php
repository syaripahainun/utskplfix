<?php

namespace App\Http\Middleware;

use Closure;

class Cektoken
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
        if ($request->has('api_token')) {
            $check_token = User::where('api_token', $apiToken)->first();
            if ($check_token == null) {
                $res['success'] = false;
                $res['message'] ='Permission not allowed';
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }
        return $next($request);
    }
}