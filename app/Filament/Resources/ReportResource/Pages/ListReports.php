<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Filament\Resources\ReportResource\Widgets\Filters;
use App\Filament\Resources\ReportResource\Widgets\OrdersChart;
use App\Filament\Resources\ReportResource\Widgets\SalesFilter;
use App\Filament\Resources\ReportResource\Widgets\SalesSummary;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use BezhanSalleh\FilamentGoogleAnalytics\Widgets;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            Filters::class,
            SalesSummary::class,
            OrdersChart::class,
            Widgets\PageViewsWidget::class,
            Widgets\VisitorsWidget::class,
            Widgets\ActiveUsersOneDayWidget::class,
            Widgets\ActiveUsersSevenDayWidget::class,
            Widgets\ActiveUsersTwentyEightDayWidget::class,
            Widgets\SessionsWidget::class,
            Widgets\SessionsDurationWidget::class,
            Widgets\SessionsByCountryWidget::class,
            Widgets\SessionsByDeviceWidget::class,
            Widgets\MostVisitedPagesWidget::class,
            Widgets\TopReferrersListWidget::class,
        ];
    }

    protected function getTableContent(): ?\Illuminate\View\View
    {
        return null;
    }
}
