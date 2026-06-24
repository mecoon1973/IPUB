<?php
namespace App\Http\Middleware;
use Closure;
class Cors {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $allowedOrigins = ['http://test.nxbgd.vn', 'http://nxbgd.xyz', 'http://nxbgd.vn', 'https://nxbgd.vn/', '*'];
        $origin = "*";
        if (isset($_SERVER['HTTP_REFERER']))
        	$origin = trim($_SERVER['HTTP_REFERER'],'/');

        if (in_array($origin, $allowedOrigins)) {
            $response = $next($request);
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With, X-Token-Auth, Content-Type, Authorization, X-XSRF-TOKEN');
            if($origin == 'https://account.olm.vn') {
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
            }
            return $response;
        }

        return $next($request);
    }
}
