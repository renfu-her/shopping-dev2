<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Member;

class RestoreMemberSession
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
        // Check if user is not authenticated but we have member session data
        if (!auth()->guard('member')->check() && session('member_id')) {
            $member = Member::find(session('member_id'));
            if ($member) {
                auth()->guard('member')->login($member);
                
                // Clear the stored session data after successful login
                session()->forget(['member_id', 'member_email']);
            }
        }

        return $next($request);
    }
}
