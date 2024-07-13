<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\SafeSubmit\SafeSubmit;

class GenerateSafeSubmitToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $safeSubmit = app(SafeSubmit::class);

        if($this->isReading($request)){
            $safeSubmit->regenerateToken();
        }

        return $next($request);
    }

    protected function isReading($request){
        return in_array($request->method(), ['HEAD', 'GET', 'OPTIONS']);
    }
}
