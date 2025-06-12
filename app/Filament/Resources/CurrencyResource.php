<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Currency;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CurrencyResource\Pages\EditCurrency;
use App\Filament\Resources\CurrencyResource\Pages\CreateCurrency;
use App\Filament\Resources\CurrencyResource\Pages\ListCurrencies;
use Mvenghaus\FilamentPluginTranslatableInline\Forms\Components\TranslatableContainer;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return __('site.all_currencies');
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
                    ->label(__('site.currency_code'))
                    ->required()
                    ->maxLength(3)
                    ,
                    
                TextInput::make('symbol')
                    ->label(__('site.currency_symbol'))
                    ->required()
                    // ->maxLength(5)
                    ,
                    
                TextInput::make('order')
                    ->label(__('site.display_order'))
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                    
                TextInput::make('exchange_rate')
                    ->label(__('site.exchange_rate'))
                    ->required()
                    ->numeric()
                    ->step(0.000001),
                    
              
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('site.name'))
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('code')
                    ->label(__('site.code'))
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('symbol')
                    ->label(__('site.symbol'))
                    ->searchable(),
                    
                TextColumn::make('exchange_rate')
                    ->label(__('site.exchange_rate'))
                    ->numeric(
                        decimalPlaces: 6,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ),
                    
                TextColumn::make('order')
                    ->label(__('site.order'))
                    ->sortable(),
                    
               
            ])
            ->filters([
               
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('site.edit')),
                    
                Tables\Actions\DeleteAction::make()
                    ->label(__('site.delete'))
                    ,
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label(__('site.bulk_delete'))
                    ->before(function (Collection $records) {
                        // Prevent deletion if any currency is default
                        $defaults = $records->filter(fn ($record) => $record->is_default);
                        if ($defaults->isNotEmpty()) {
                            throw new \Exception(__('site.cannot_delete_default_currencies'));
                        }
                    }),
                    
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('set_default')
                        ->label(__('site.set_as_default'))
                        ->action(function (Collection $records) {
                            // Only allow one default currency
                            Currency::query()->update(['is_default' => false]);
                            $records->first()->update(['is_default' => true, 'exchange_rate' => 1]);
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->icon('heroicon-o-star'),
                ]),
            ])
            ->defaultSort('order', 'asc');
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
            'index' => ListCurrencies::route('/'),
            'create' => CreateCurrency::route('/create'),
            'edit' => EditCurrency::route('/{record}/edit'),
        ];
    }
}