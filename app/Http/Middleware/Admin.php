<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    protected $unauthenticatedIsRedirectTo = 'login';
    protected $unauthorizedIsRedirectTo = 'dashboard';

    /**
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     *
     * @return \Illuminate\Http\RedirectResponse|mixed|void
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        if(! auth()->check()) {
            return Redirect::route($this->unauthenticatedIsRedirectTo);
        }

        if (! $request->user()->isAdmin()) {
            return $request->expectsJson()
                ? abort(Response::HTTP_FORBIDDEN, 'You can not access administration area!')
                : Redirect::route($this->unauthorizedIsRedirectTo)
                    ->with('message', 'You can not access administration area!');
        }

        return $next($request);
    }
}
