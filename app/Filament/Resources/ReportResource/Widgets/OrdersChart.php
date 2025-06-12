<?php

namespace App\Filament\Resources\ReportResource\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'الطلبات';
    public ?array $filters = [];

    protected function getData(): array
    {
        // Get date filters and ensure proper Carbon instances
        $from = !empty($this->filters['from'])
            ? Carbon::parse($this->filters['from'])->startOfDay()
            : now()->subYear()->startOfDay();

        $to = !empty($this->filters['to'])
            ? Carbon::parse($this->filters['to'])->endOfDay()
            : now()->endOfDay();

        // Query orders with payment_status = paid
        $query = Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$from, $to]);

        // Determine appropriate grouping based on date range
        $diffInDays = $to->diffInDays($from);

        if ($diffInDays <= 1) {
            // Today/Yesterday - group by hour
            $data = Trend::query($query)
                ->between($from, $to)
                ->perHour()
                ->count();

            $labels = $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('g:i A'));
        } elseif ($diffInDays <= 7) {
            // Week - group by day
            $data = Trend::query($query)
                ->between($from, $to)
                ->perDay()
                ->count();

            $labels = $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->translatedFormat('l'));
        } elseif ($diffInDays <= 31) {
            // Month - group by day
            $data = Trend::query($query)
                ->between($from, $to)
                ->perDay()
                ->count();

            $labels = $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('d M'));
        } else {
            // Longer periods - group by month
            $data = Trend::query($query)
                ->between($from, $to)
                ->perMonth()
                ->count();

            $arMonths = [
                'Jan' => 'يناير', 'Feb' => 'فبراير', 'Mar' => 'مارس',
                'Apr' => 'أبريل', 'May' => 'مايو', 'Jun' => 'يونيو',
                'Jul' => 'يوليو', 'Aug' => 'أغسطس', 'Sep' => 'سبتمبر',
                'Oct' => 'أكتوبر', 'Nov' => 'نوفمبر', 'Dec' => 'ديسمبر'
            ];

            $labels = $data->map(function (TrendValue $value) use ($arMonths) {
                $date = Carbon::parse($value->date);
                return $arMonths[$date->format('M')] . ' ' . $date->format('Y');
            });
        }

        return [
            'datasets' => [
                [
                    'label' => 'عدد الطلبات',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getListeners(): array
    {
        return [
            'updateFilters' => 'updateFilters',
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0
                    ]
                ]
            ]
        ];
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
    public function updateFilters(array $filters): void
    {
        $this->filters = $filters;
        $this->updateChartData();
    }
}
