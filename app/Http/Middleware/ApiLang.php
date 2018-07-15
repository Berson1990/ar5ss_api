<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class ApiLang
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
        if($request->has('lang') ){
            App::setLocale($request->input('lang'));
        }
        return $next($request);
    }
}
