<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BabySitterSecondStep
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
        $baby_sitter = Auth::user();
        if ($baby_sitter->is_accepted != 1) {
            $data['success'] = false;
            $data['message'] = 'Öncelikle Yöneticilerimizden Onaylanmanız Gerekmektedir!';
            return response()->json($data);
        }
        return $next($request);
    }
}
