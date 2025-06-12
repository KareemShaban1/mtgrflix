<?php

namespace App\Filament\Resources\ReportResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesSummary extends BaseWidget
{
    protected function getHeading(): ?string
    {
        return __('filament.sales');
    }

    public ?array $filters = [];

    protected function getStats(): array
    {
        $query = Order::query()->where('payment_status', 'paid', 'payment');

        if (!empty($this->filters['from'])) {
           $query->whereDate('orders.created_at', '>=', $this->filters['from']);
        }
        if (!empty($this->filters['to'])) {
           $query->whereDate('orders.created_at', '<=', $this->filters['to']);
        }

        $totalSales = $query->sum('sub_total');
        $totalDiscounts = $query->sum('discount');
        $totalSalesAfterDiscounts = $totalSales - $totalDiscounts;

        $orders = $query->with('payment')->get();

        $totalPaymentFees = $query->clone()
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->sum('payments.tax');
        $netSales = $totalSalesAfterDiscounts - $totalPaymentFees;

        return [
            Stat::make(__('filament.total_sales_before_coupons'), number_format($totalSales, 2) . ' SAR')
                ->description('اجمالي المبيعات (شامل التخفيضات)')
                ->color('success')
                ->icon('heroicon-o-shopping-cart'),

            Stat::make(__('filament.coupons_discount'), number_format($totalDiscounts, 2) . ' SAR')
                ->description('إجمالي التخفيضات')
                ->color('danger')
                ->icon('heroicon-o-tag'),

            Stat::make(__('filament.total_sales_after_coupons'), number_format($totalSalesAfterDiscounts, 2) . ' SAR')
                ->description('اجمالي المبيعات')
                ->color('success')
                ->icon('heroicon-o-shopping-cart'),

            Stat::make(__('filament.payment_fees'), number_format($totalPaymentFees, 2) . ' SAR')
                ->description('رسوم الدفع الالكتروني')
                ->color('danger')
                ->icon('heroicon-o-banknotes'),

            Stat::make(__('filament.net_sales'), number_format($netSales, 2) . ' SAR')
                ->description('صافي المبيعات')
                ->color('primary')
                ->icon('heroicon-o-currency-dollar'),
        ];
    }

    protected function getDateRangeDescription(): string
    {
        if (empty($this->filters['from']) && empty($this->filters['to'])) {
            return 'All Time';
        }

        $from = $this->filters['from'] ? date('d/m/Y', strtotime($this->filters['from'])) : 'Beginning';
        $to = $this->filters['to'] ? date('d/m/Y', strtotime($this->filters['to'])) : 'Now';

        return "From $from to $to";
    }

    protected function getListeners(): array
    {
        return [
            'updateFilters' => 'updateFilters',
        ];
    }

    public function updateFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
}
