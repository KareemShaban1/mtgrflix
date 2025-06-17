<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\Referral;
use Illuminate\Support\Facades\Session;

class TrackReferralVisits
{
    public function handle($request, Closure $next)
    {
        $refId = $request->query('ref');

        if ($refId) {
            $referral = Referral::firstOrCreate(['ref_id' => $refId]);

            // Avoid duplicate count during same session
            if (!Session::has("visited_ref_{$refId}")) {
                $referral->increment('visits_count');
                Session::put("visited_ref_{$refId}", true);
            }

            // Store for later purchase tracking
            Session::put('ref_id', $refId);
        }

        return $next($request);
    }
}
