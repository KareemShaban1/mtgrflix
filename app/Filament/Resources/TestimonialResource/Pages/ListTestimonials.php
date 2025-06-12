<?php

namespace App\Filament\Resources\TestimonialResource\Pages;

use App\Filament\Resources\TestimonialResource;
use App\Models\Testimonial;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestimonials extends ListRecords
{
    protected static string $resource = TestimonialResource::class;

    public function mount(): void
    {
        parent::mount();

        Testimonial::whereNull('viewed_at')->update([
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
