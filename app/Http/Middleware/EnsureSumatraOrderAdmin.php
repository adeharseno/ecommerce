<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSumatraOrderAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $emails = array_map('strtolower', config('sumatra-orders.admin_emails', []));
        $email = strtolower((string) $request->user()?->email);
        abort_unless($email !== '' && in_array($email, $emails, true), 403);

        return $next($request);
    }
}
