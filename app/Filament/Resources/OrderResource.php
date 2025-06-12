<?php

namespace App\Filament\Resources;

use Dom\Text;
use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Status;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use App\Filament\Imports\OrderImporter;
use App\Notifications\UserNotification;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Actions\Imports\Jobs\ImportCsv;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\OrderItemsRelationManager;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $recordTitleAttribute = 'number';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 5;



    public static function getNavigationLabel(): string
    {
        return __('site.orders');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make(__('site.order_information'))->schema([ // Added 'site.' prefix
                        Select::make('user_id')
                            ->label(__('site.customer'))
                            ->relationship('user', 'mobile')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->disabled(fn($record) => $record !== null), // Disable for editing on update

                        TextInput::make('payment_method')
                            // ->required()
                            ->disabled(fn($record) => $record !== null), // Disable for editing on update

                        TextInput::make('payment_status')
                            ->default('pending')
                            // ->required()
                            ->disabled(fn($record) => $record !== null), // Disable for editing on update
                        TextInput::make('status')
                            // ->default('pending')
                            ->label(__('site.status')) // Added 'site.' prefix
                            // ->required()
                            ->disabled(fn($record) => $record !== null), // Disable for editing on update
                        ToggleButtons::make('status_id')
                            ->label(__('site.status')) // Added 'site.' prefix
                            ->inline()
                            ->default('new')
                            ->options(Status::all()->pluck('name', 'id')->toArray())
                            ->colors([
                                'new' => 'info',
                                'processing' => 'warning',
                                'shipped' => 'success',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'cancelled' => 'heroicon-m-x-circle',
                            ])
                            ->reactive(), // Keeping the status editable

                        Select::make('currency_id')
                            ->relationship(name: 'currency', titleAttribute: 'name')
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->getTranslation('name', 'en')} - {$record->getTranslation('name', 'ar')}")
                            ->searchable()
                            // ->required()
                            ->label(__('site.product_currency')) // Added 'site.' prefix
                            ->preload()
                            ->columnSpanFull()
                            ->disabled(fn($record) => $record !== null), // Disable for editing on update

                    ])->columns(2),

                    Section::make(__('site.order_items'))->schema([ // Added 'site.' prefix
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->distinct()
                                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->getTranslation('name', 'en')} - {$record->getTranslation('name', 'ar')}")
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(4)
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set) => $set('unit_amount', Product::find($state)?->price ?? 0))
                                    ->afterStateUpdated(fn($state, Set $set) => $set('total_amount', Product::find($state)?->price ?? 0))
                                    ->disabled(fn($record) => $record !== null), // Disable for editing on update

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1)
                                    ->columnSpan(2)
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount')))
                                    ->disabled(fn($record) => $record !== null), // Disable for editing on update

                                TextInput::make('unit_amount')
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(3)
                                    ->disabled(fn($record) => $record !== null), // Disable for editing on update

                                TextInput::make('total_amount')
                                    ->numeric()
                                    ->required()
                                    ->dehydrated()
                                    ->columnSpan(3)
                                    ->disabled(fn($record) => $record !== null), // Disable for editing on update

                                Repeater::make('itemOptions')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('type')
                                            ->required()
                                            ->placeholder(__('site.select_type')) // Added 'site.' prefix
                                            ->label(__('site.type')) // Added 'site.' prefix
                                            ->disabled(fn($record) => $record !== null), // Disable for editing on update

                                        TextInput::make('field_name')
                                            ->nullable()
                                            ->label(__('site.field_name')) // Added 'site.' prefix
                                            ->disabled(fn($record) => $record !== null), // Disable for editing on update

                                        TextInput::make('key')
                                            ->nullable()
                                            ->visible(fn(Forms\Get $get) => $get('type') === 'select')
                                            ->label(__('site.key')) // Added 'site.' prefix
                                            ->disabled(fn($record) => $record !== null), // Disable for editing on update

                                        Textarea::make('value')
                                            ->required()
                                            ->label(__('site.value')) // Added 'site.' prefix
                                            ->rows(3)
                                            ->disabled(fn($record) => $record !== null), // Disable for editing on update
                                    ])
                                    ->defaultItems(1)
                                    ->columns(2)
                                    ->columnSpanFull()
                            ])->columns(12)->disabled(fn($record) => $record !== null),

                        Placeholder::make('grand_total_placeholder')
                            ->label(__('site.grand_total')) // Added 'site.' prefix
                            ->content(function (Get $get, Set $set) {
                                $total = 0;
                                if (!$repeaters = $get('items')) {
                                    return $total;
                                }

                                foreach ($repeaters as $key => $repeaters) {
                                    $total += $get("items.{$key}.total_amount");
                                }
                                $set('grand_total', $total);
                                return Number::currency($total, session('currency', 'SAR'), true);
                            }),

                        Hidden::make('grand_total')
                            ->default(0)
                    ]),

                    Section::make(__('site.cart_summery'))->schema([
                        TextInput::make('product_items')
                            ->disabled(fn($record) => $record !== null), // Disable for editing on update
                    ])->columns(2),

                ])->columnSpanFull()
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->persistFiltersInSession()
            ->paginated([10, 25, 50, 100])
            ->columns([
                TextColumn::make('number')
                    ->label(__('site.order_number'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label(__('site.customer'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('grand_total')
                    ->label(__('site.total_amount'))
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => Number::currency($state, 'SAR'))
                    ->searchable()
                    ->summarize(Sum::make()->label(__('site.sum'))),

                TextColumn::make('sub_total')
                    ->label(__('site.total_amount_before_discount'))
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => Number::currency($state, 'SAR'))
                    ->searchable()
                    ->summarize(Sum::make()->label(__('site.sum'))),

                TextColumn::make('discount')
                    ->label(__('site.discount'))
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => Number::currency($state, 'SAR'))
                    ->searchable()
                    ->summarize(Sum::make()->label(__('site.sum'))),

                TextColumn::make('payment.tax')
                    ->label(__('site.vat_amount'))
                    ->formatStateUsing(fn($state) => Number::currency($state, 'SAR'))
                    ->summarize(Sum::make()->label(__('site.sum'))),

                TextColumn::make('payment_method')
                    ->label(__('site.payment_method'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->label(__('site.payment_status'))
                    ->searchable()
                    ->sortable(),

                SelectColumn::make('status_id')
                    ->label(__('site.status'))
                    ->options(Status::all()->mapWithKeys(fn($status) => [
                        $status->id => $status->getTranslation('name', app()->getLocale())
                    ])->toArray())
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('site.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('site.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label(__('site.created_from')),
                        DatePicker::make('created_until')->label(__('site.created_until')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    // EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->headerActions([
                ImportAction::make()
                    ->label(__('site.import_orders'))
                    ->importer(OrderImporter::class)
                    ->chunkSize(250)
                    ->job(ImportCsv::class)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->action(function (Collection $records, array $data): void {
                            $status = Status::find($data['status_id']);

                            foreach ($records as $record) {
                                $record->status_id = $data['status_id'];
                                $record->save();
                                // Send notification to user
                                $record->user->notify(
                                    new UserNotification($record->id, $status->name)
                                );
                            }
                        })
                        ->form([
                            Select::make('status_id')
                                ->label(__('site.status'))
                                ->options(Status::all()->pluck('name', 'id')->toArray())
                                ->required(),
                        ])
                        ->icon('heroicon-o-pencil')
                        ->label(__('Update Status'))
                        ->deselectRecordsAfterCompletion()
                        ->after(function () {
                            Notification::make()
                                ->title('Status updated successfully')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }


    // public static function getRelations(): array
    // {
    //     return [
    //         OrderItemsRelationManager::class
    //     ];
    // }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('payment_status', 'paid')->whereNull('viewed_at')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 0 ? 'success' : 'danger';
    }

    public static function getEloquentQuery(): Builder
    {        $query = parent::getEloquentQuery();

        // Only apply these filters to the index page (list view)
        if (request()->routeIs('filament.admin.resources.orders.index')) {
            return parent::getEloquentQuery()
                ->where('payment_status', 'paid')
                ->orWhere('status', 'تم التنفيذ')
                ->latest();
        }

        // For all other cases (view, edit, etc.), return unfiltered query
        return $query;
    }

    public static function getGlobalSearchResultUrl(\Illuminate\Database\Eloquent\Model $record): string
    {
        return static::getUrl('view', ['record' => $record->getKey()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
