<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->is_admin) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Accès réservé aux administrateurs.']);
        }
        return $next($request);
    }
}
