<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvertisementResource\Pages;
use App\Filament\Resources\AdvertisementResource\RelationManagers;
use App\Models\Advertisement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdvertisementResource extends Resource
{
    protected static ?string $model = Advertisement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    public static function getNavigationLabel(): string
    {
        return __('site.advertisement');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('text')
                    ->label(__('site.text'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('color')
                    ->label(__('site.color'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('background')
                    ->label(__('site.background'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('text')
                    ->label(__('site.text'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->label(__('site.color'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('background')
                    ->label(__('site.background'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(
            fn (Advertisement $record): string => static::getUrl('edit', ['record' => $record]),
        );
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvertisements::route('/'),
            'edit' => Pages\EditAdvertisement::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
