<?php

namespace App\Http\Middleware;

use App\Models\Status;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDealStatus
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $deal = $request->route('deal');

        if ($deal->status_id !== Status::DEAL_IN_PROGRESS) {
            return response()->json(['error' => 'This deal can only be updated if it is in progress.'], 400);
        }

        return $next($request);
    }
}
