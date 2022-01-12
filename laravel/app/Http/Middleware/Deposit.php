<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Deposit
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
        $baby_sitter=Auth::user();
        if ($baby_sitter->deposit<30){
            $data['success'] = false;
            $data['message'] = 'Öncelikle Depozito Ödemenizi Yapmanız Gerekmektedir!';
            return response()->json($data);
        }
        return $next($request);
    }
}
