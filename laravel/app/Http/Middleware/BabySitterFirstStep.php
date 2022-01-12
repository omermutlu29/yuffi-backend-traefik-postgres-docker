<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BabySitterFirstStep
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $baby_sitter = Auth::user();
        if ($baby_sitter->name == null ||
            $baby_sitter->surname == null ||
            $baby_sitter->gender_id == null ||
            $baby_sitter->birthday == null ||
            $baby_sitter->iban == null ||
            $baby_sitter->tc == null ||
            $baby_sitter->criminal_record == null
        ) {
            $data['success'] = false;
            $data['message'] = 'Henüz temel bilgilerinizi doldurmamış görünüyorsunuz!';
            return response()->json($data);
        }
        return $next($request);
    }
}
