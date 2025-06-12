<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Check if there's a lang query string (e.g., ?lang=ar)
         if ($request->has('lang')) {
            $locale = $request->get('lang');
            Session::put('locale', $locale);
        }

        // Get the locale from session or fallback to app default
        $locale = Session::get('locale', config('app.locale'));

        App::setLocale($locale);

        return $next($request);
    }
}
