<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IPWhitelist;

class WhiteListIpAddressessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(getConfig("IP_WHITELIST")){
            $whitelistIps = IPWhitelist::get()->pluck("ip")->toArray();
            if (!in_array($request->getClientIp(), $whitelistIps)) {
                abort(403, "IP KAMU TIDAK BISA MEMBUKA SERVER INI.");
            }
        }

        return $next($request);
    }
}
