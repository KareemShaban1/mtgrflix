<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\CountryResource\Pages\EditCountry;
use App\Filament\Resources\CountryResource\Pages\CreateCountry;
use App\Filament\Resources\CountryResource\Pages\ListCountries;
use Mvenghaus\FilamentPluginTranslatableInline\Forms\Components\TranslatableContainer;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?int $navigationSort = 6;

    public static function getNavigationLabel(): string
    {
        return __('site.all_countries');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TranslatableContainer::make(
                    TextInput::make('name')
                        ->label(__('site.name'))
                        ->required()
                        ->live(onBlur: true)
                        
                )->columnSpanFull(),
                    
                TextInput::make('code')
                    ->label(__('site.country_code'))
                    ->required()
                    // ->maxLength(2)
                   
                    
                    ,
                    
                // FileUpload::make('flag')
                //     ->label(__('site.flag_image'))
                //     ->directory('flags')
                //     ->image()
                //     ->imageResizeMode('force')
                //     ->imageResizeTargetWidth('100')
                //     ->imageResizeTargetHeight('60')
                //     ->imagePreviewHeight('60')
                //     ->downloadable()
                //     ->openable()
                //     ->maxSize(1024),
                    
                TextInput::make('order')
                    ->label(__('site.display_order'))
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(20),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ImageColumn::make('flag')
                //     ->label(__('site.flag'))
                //     ->size(40)
                //     ->extraImgAttributes(['class' => 'object-cover']),
                    
                TextColumn::make('name')
                    ->label(__('site.name'))
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('code')
                    ->label(__('site.code'))
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('order')
                    ->label(__('site.order'))
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->label(__('site.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('site.edit')),
                    
                Tables\Actions\DeleteAction::make()
                    ->label(__('site.delete'))
                    ->before(function (Country $record) {
                        // Add any pre-deletion checks here
                        if ($record->flag) {
                            Storage::delete($record->flag);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label(__('site.bulk_delete'))
                    ->before(function (Collection $records) {
                        // Delete associated flag files
                        $records->each(function ($record) {
                            if ($record->flag) {
                                Storage::delete($record->flag);
                            }
                        });
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCountries::route('/'),
            'create' => CreateCountry::route('/create'),
            'edit' => EditCountry::route('/{record}/edit'),
        ];
    }
}