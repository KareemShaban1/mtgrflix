<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OrderStats extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {

        if (!auth()->user()->hasRole('super_admin')) {
            return [];
        }

        return [

            // Today's paid amounts
            Stat::make(
                __('filament.today_amounts'),
                Number::currency(
                    Order::query()
                        ->where('payment_status', 'paid')
                        ->whereDate('created_at', today())
                        ->sum('grand_total'),
                    'SAR'
                )
            ),

            // This month's paid amounts
            Stat::make(
                __('filament.this_month_amounts'),
                Number::currency(
                    Order::query()
                        ->where('payment_status', 'paid')
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->sum('grand_total'),
                    'SAR'
                )
            ),

            // Calculate the total orders for different time periods
            // Today's paid orders count
            Stat::make(__('filament.today_orders'), Order::query()
                        ->where('payment_status', 'paid')
                        ->whereDate('created_at', today())
                        ->count()
            ),

            // This month's paid orders count
            Stat::make(__('filament.this_month_orders'), Order::query()
                        ->where('payment_status', 'paid')
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count()
            ),

            Stat::make(__('filament.monthly_visits'), Order::query()->whereBetween('created_at', [
                            now()->subMonth(),
                            now()
                        ])
                        ->count()
            ),

        ];
    }
}
