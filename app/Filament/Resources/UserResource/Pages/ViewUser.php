<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Dom\Text;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label(__('site.edit')),
            Actions\DeleteAction::make()->label(__('site.delete')),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('filament.customer_information'))
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('name')
                                    ->label(__('site.name')),

                                TextEntry::make('gender')
                                    ->label(__('filament.gender')),

                                TextEntry::make('birthday')
                                    ->label(__('site.birthday')),

                                TextEntry::make('created_at')
                                    ->label(__('filament.created_at_in_site'))
                                    ->formatStateUsing(fn ($state) => $state->locale('en')->translatedFormat('l j F Y | h:i A')),

                                TextEntry::make('email')
                                    ->label(__('site.email')),

                                TextEntry::make('mobile')
                                    ->label(__('site.mobile')),

                                TextEntry::make('mobile')
                                    ->label(__('filament.whatsapp'))
                                    ->formatStateUsing(function (?string $state) {
                                        if (!$state) return null;

                                        $mobile = preg_replace('/[^0-9]/', '', $state);
                                        $whatsappUrl = "https://wa.me/{$mobile}";

                                        return "<a href='{$whatsappUrl}' target='_blank' class='text-primary-500 hover:underline'>{$state}</a>";
                                    })
                                    ->html(),

                                TextEntry::make('address')
                                    ->label(__('site.address'))
                                    ->default(__('filament.no_address'))

                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

