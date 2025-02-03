<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Filament\Facades\Filament;

use Closure;

class EnsureFilamentPanelAccess
{
    private string $context;

    private ?User $user;
    
    private bool $isLoggedIn = false;

    private bool $isAdminPanel = false;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $user = auth()->user();
        $panel = Filament::getCurrentPanel();

        if (! $panel) {
            abort(404, 'Panel not found.');
        }

        $this->user = $user;
        $this->isLoggedIn = (bool) $user;
        $this->context = $panel->getId() ?? config('plagiarism-checker.panels.user.id');
        $this->isAdminPanel = $this->context === config('plagiarism-checker.panels.admin.id');

        if (! $this->hasPanelAccess()) {
            return $this->redirectTo();
        }

        return $next($request);
    }

    /**
     * Redirect to the login page of the current panel.
     *
     */
    private function redirectTo(): \Illuminate\Http\RedirectResponse
    {
        $panel = $this->user->isAdmin()
            ? config('plagiarism-checker.panels.admin.id')
            : config('plagiarism-checker.panels.user.id');

        $route = $this->isLoggedIn ? 'dashboard' : 'login';

        return redirect(route(config("plagiarism-checker.panels.$panel.routes.$route")));
    }

    /**
     * Check if the user has access to the current panel.
     *
     * @param  \App\Models\User  $user  
     */
    private function hasPanelAccess(): bool
    {
        if ($this->isAdminPanel && $this->user->isAdmin()) {
            return true;
        }
        
        return !$this->isAdminPanel && !$this->user->isAdmin();
    }
}
