<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\RoleAccess;
use Illuminate\Http\Request;

class RoleAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $url)
    {
        $access = RoleAccess::where('role_id', session('role_id'))
            ->whereHas('menu', function($query) use ($url) {
                $query->where('url', $url);
            })
            ->count();

        if($access > 0) {
            return $next($request);
        } else {
            return abort(403);
        }
    }
}
