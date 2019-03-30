<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    protected $unverifiedIsRedirectTo = 'home';

    /**
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     * @param mixed                    ...$guards
     *
     * @return \Illuminate\Http\RedirectResponse|mixed|void
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                ! $request->user()->hasVerifiedEmail())) {
            return $request->expectsJson()
                ? abort(Response::HTTP_FORBIDDEN, 'Your email address is not verified.')
                : Redirect::route($this->unverifiedIsRedirectTo)
                    ->with('message', 'You must confirm your email first!');
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
