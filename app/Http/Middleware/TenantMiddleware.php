<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has a company - if not, redirect to company setup
        if (!$user->company_id) {
            return redirect()->route('setup.company')->with('info', 'Please set up your company to continue.');
        }

        // Check if company is active
        if (!$user->company->is_active) {
            return redirect()->route('subscription.suspended')->with('error', 'Your company account is suspended.');
        }

        // Check if company has active subscription
        if (!$user->company->hasActiveSubscription()) {
            return redirect()->route('subscription.expired')->with('error', 'Your subscription has expired.');
        }

        // Set the current tenant (company) in the request
        $request->merge(['tenant_id' => $user->company_id]);

        // Store company info for easy access in views
        view()->share('currentCompany', $user->company);
        view()->share('currentUser', $user);

        return $next($request);
    }
}
