<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use Carbon\Carbon;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function mount(): void
    {
        parent::mount();

        Order::whereNull('viewed_at')->update([
            'viewed_at' => Carbon::now(),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array {
        return [
            OrderStats::class
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make(__('site.all')), // Localized "All" tab
            'pending' => Tab::make(__('site.pending'))
                ->query(fn ($query) => $query->where('status_id', 1))
                ->icon('heroicon-o-clock'),
            'shipped' => Tab::make(__('site.shipped'))
                ->query(fn ($query) => $query->where('status_id', 2))
                ->icon('heroicon-o-truck'),
            'completed' => Tab::make(__('site.completed'))
                ->query(fn ($query) => $query->where('status_id', 3))
                ->icon('heroicon-o-check-circle'),
            'cancelled' => Tab::make(__('site.cancelled'))
                ->query(fn ($query) => $query->where('status_id', 4))
                ->icon('heroicon-o-x-circle'),
            'declined' => Tab::make(__('site.declined'))
                ->query(fn ($query) => $query->where('status_id', 5))
                ->icon('heroicon-o-no-symbol'),
        ];
    }
}
