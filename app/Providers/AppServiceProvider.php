<?php

namespace App\Providers;

use App\Models\Advertisement;
use App\Models\User;
use App\Models\Country;
use App\Models\Category;
use App\Models\Currency;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::define('use-translation-manager', function (?User $user) {
            // Your authorization logic
            return true;
        });
        $categories = Category::where('is_active', 1)->whereNotNull('parent_id')->get();
        $currencies = Currency::orderBy('order')->get();
        $countries = Country::orderBy('order')->get();
        $advertisement = Advertisement::find(1)->first();
        // view()->share('categories', 'currencies', 'currentCurrency');

        View::share([

            'categories' => $categories,
            'currencies' => $currencies,
            'countries' => $countries,
            'currentCurrency' => session('currency', 'SAR'), // default to USD
            // 'symbol' => session()->get('symbol', 'riyal'), // default to $
            'advertisement' => $advertisement,
        ]);
        FilamentAsset::register([
            Css::make('custom-stylesheet', __DIR__ . '/../../resources/css/custom.css'),
        ]);
    }
}
