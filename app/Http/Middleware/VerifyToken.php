<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (empty(session('token'))) {
            return redirect(route('login'))
            ->with('error', 'You are not logged in!');
        }

        $authUser = User::query()
            ->where([
                'username' => session('user'),
                'remember_token' => session('token')
            ])
            ->first();
        
        if (! $authUser) {
            return redirect(route('login'))
            ->with('error', 'Warning! Invalid account credentials, your account might be logged in another device!');
        }

        return $next($request);
    }
}
