<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Coupon;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\CouponResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CouponResource\Pages\EditCoupon;
use App\Filament\Resources\CouponResource\Pages\CreateCoupon;
use App\Filament\Resources\CouponResource\Pages\ListCoupons;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('site.coupons');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label(__('site.coupon_code'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\Select::make('type')
                    ->label(__('site.discount_type'))
                    ->required()
                    ->options([
                        'percentage' => __('site.percentage'),
                        'fixed' => __('site.fixed_amount'),
                    ]),

                Forms\Components\TextInput::make('value')
                    ->label(function ($get) {
                        return $get('type') === 'percentage'
                            ? __('site.discount_percentage')
                            : __('site.discount_amount');
                    })
                    ->required()
                    ->numeric(),

                Forms\Components\DateTimePicker::make('valid_from')
                    ->label(__('site.valid_from'))
                    ->required(),

                Forms\Components\DateTimePicker::make('valid_until')
                    ->label(__('site.valid_until'))
                    ->required(),

                Forms\Components\TextInput::make('max_uses')
                    ->label(__('site.max_uses'))
                    ->numeric()
                    ->nullable(),

                Forms\Components\TextInput::make('max_uses_per_user')
                    ->label(__('filament.max_uses_per_user'))
                    ->numeric()
                    ->nullable(),

                // Forms\Components\TextInput::make('min_purchase_amount')
                //     ->label(__('site.min_purchase_amount'))
                //     ->numeric()
                //     ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('site.coupon_code'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('site.discount_type'))
                    ->formatStateUsing(fn(string $state): string =>
                    $state === 'percentage'
                        ? __('site.percentage')
                        : __('site.fixed_amount')),

                Tables\Columns\TextColumn::make('value')
                    ->label(__('site.value')),

                Tables\Columns\TextColumn::make('valid_from')
                    ->label(__('site.valid_from'))
                    ->date(),

                Tables\Columns\TextColumn::make('valid_until')
                    ->label(__('site.valid_until'))
                    ->date(),

                Tables\Columns\TextColumn::make('uses_count')
                    ->label(__('site.uses_count')),

                Tables\Columns\TextColumn::make('unique_users_count')
                    ->label(__('site.client_count')),

                Tables\Columns\TextColumn::make('total_discount')
                    ->label(__('site.total_discount')),


                Tables\Columns\TextColumn::make('max_uses')
                    ->label(__('site.max_uses')),

                Tables\Columns\TextColumn::make('max_uses_per_user')
                    ->label(__('filament.max_uses_per_user')),
            ])
            ->filters([
                // Tables\Filters\Filter::make('active')
                //     ->label(__('site.active_coupons'))
                //     ->query(fn (Builder $query): Builder => $query->where('valid_until', '>=', now())),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('site.edit')),

                Tables\Actions\DeleteAction::make()
                    ->label(__('site.delete')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label(__('site.bulk_delete')),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
         if (request()->routeIs('filament.admin.resources.coupons.index')) {
            return parent::getEloquentQuery()
                ->has('orders')
                // ->doesntHave('cart')
                // ->doesntHave('testimonial')
                // ->whereNull('max_uses')
                ->latest();
        }

        return parent::getEloquentQuery();
    }
    public static function getPages(): array
    {
        return [
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}
