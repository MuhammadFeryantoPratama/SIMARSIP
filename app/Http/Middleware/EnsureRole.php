<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_unless($user !== null, 401);
        $role = $user->role?->name;
        $allowed = collect($roles)->contains(function (string $requiredRole) use ($role): bool {
            return match ($requiredRole) {
                'Staff' => in_array($role, ['Staff', 'Editor Arsip', 'Viewer'], true),
                'Pimpinan' => in_array($role, ['Pimpinan', 'Validator'], true),
                default => $role === $requiredRole,
            };
        });

        abort_unless($allowed, 403);

        return $next($request);
    }
}
