<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;

use Closure;

class AdminMiddleware
{
    private ?User $user;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->user = auth()->user();

        if (!$this->user->isAdmin()) {
            $this->redirectToUserLoginRoute();
        }

        return $next($request);
    }

    /**
     * Redirect to the login page of the current panel.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToUserLoginRoute()
    {
        $userLoginRoute = config('plagiarism-checker.panels.user.routes.login');

        return redirect(route($userLoginRoute));
    }
}
