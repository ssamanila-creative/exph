<?php

namespace Frontier\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdmin
{
    /**
     * The redirect route for guests.
     * @var string
     */
    protected $redirectGuest = 'login';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }

            $this->flashSession();

            return redirect()->guest($this->redirectGuest);
        }

        return $next($request);
    }

    public function flashSession()
    {
        session()->flash('message', 'You need to login.');
    }
}
