<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use App\Models\Review;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReviews extends ListRecords
{
    protected static string $resource = ReviewResource::class;

    public function mount(): void
    {
        parent::mount();

        Review::whereNull('viewed_at')->update([
            'viewed_at' => Carbon::now(),
        ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
