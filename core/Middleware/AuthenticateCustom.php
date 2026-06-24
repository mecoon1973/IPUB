<?php

namespace Core\Middleware;

use Closure;

use App\Exceptions\RemoteException;
use Illuminate\Support\Facades\Auth;

class AuthenticateCustom
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
        if (!Auth::check()) {
            return redirect()->route('dang-nhap');
        }
        return $next($request);
    }
}
