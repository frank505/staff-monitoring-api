<?php

namespace App\Http\Middleware;

use Closure;

class CorsPush
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
        return $next($request)
        ->header('Content-Type', 'application/json')
        ->header('Authorization', 'key=AAAA1GdJKDE:APA91bH2z3HqSLKfNbw3Jm4dxOFADgT9G1DFTuyNtZ5zWLozcd7z6m9VXFliKmGTP62vVSoh-VtxJlEcIfi7Ho1HHHSrVVLGsTvRqueZCjmYG40b67YS6HF6ljHbf152j67BVHpV0UPI');
    }

}
