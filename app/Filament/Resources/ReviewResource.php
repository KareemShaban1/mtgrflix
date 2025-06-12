<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Review;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Imports\ReviewImporter;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Imports\Jobs\ImportCsv;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Imports\TestimonialImporter;
use App\Filament\Resources\ReviewResource\Pages;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('site.all_reviews');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'product']); // Eager loading
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->label(__('site.user'))
                ->required()
                ->relationship(
                    name: 'user',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn(Builder $query) => $query->whereNotNull('name')->limit(50)
                )
                ->searchable()
                ->getOptionLabelFromRecordUsing(fn(User $user) => $user->name ?? $user->email ?? 'Unknown'),

            Select::make('product_id')
                ->label(__('site.product'))
                ->required()
                ->relationship(
                    name: 'product',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn(Builder $query) => $query->limit(50)
                )
                ->searchable()
                ->getOptionLabelFromRecordUsing(
                    fn(Product $product) =>
                    "{$product->getTranslation('name', 'en')} - {$product->getTranslation('name', 'ar')}"
                ),

            TextInput::make('rating')
                ->label(__('site.rating'))
                ->required()
                ->numeric()
                ->minValue(1)
                ->maxValue(5),

            Textarea::make('review')
                ->label(__('site.review'))
                ->required()
                ->maxLength(2000)
                ->columnSpanFull(),

            Toggle::make('approved')
                ->label(__('site.approved'))
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer')
                    ->label(__('site.user'))
                    ->getStateUsing(function (Review $record) {
                        // If user exists, show user's name/email
                        if ($record->user) {
                            return $record->user->name ?? $record->user->email ?? 'Unknown User';
                        }
                        // Otherwise, show client_name from the review
                        return $record->client_name ?? 'Anonymous';
                    })
                    ->sortable() // Sorting will work on user.name if available
                    ->searchable(), // Search works on both user.name and client_name


                TextColumn::make('product.name')
                    ->label(__('site.product'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('rating')
                    ->label(__('site.rating'))
                    ->formatStateUsing(fn(?int $state): string => str_repeat('⭐', (int) $state)),

                TextColumn::make('review')
                    ->label(__('site.review'))
                    ->words(10)
                    // ->tooltip(fn (Review $record): string => Str::limit($record->review, 100))
                    ->searchable(),

                ToggleColumn::make('approved')
                    ->label(__('site.approved')),

                TextColumn::make('created_at')
                    ->label(__('site.created_at'))
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('approved')
                    ->label(__('site.approved_reviews'))
                    ->query(fn(Builder $query) => $query->where('approved', true)),

                Tables\Filters\Filter::make('high_rating')
                    ->label(__('site.high_rating_reviews'))
                    ->query(fn(Builder $query) => $query->where('rating', '>=', 4)),
            ])
            ->recordUrl(
                fn(Review $record): string => static::getUrl('view', ['record' => $record]),
            )
            ->actions([
                Tables\Actions\ViewAction::make()->label(__('site.view')),
                Tables\Actions\EditAction::make()->label(__('site.edit')),
                Tables\Actions\DeleteAction::make()->label(__('site.delete')),
            ])
            ->headerActions([
                ImportAction::make()
                    ->label(__('site.import_reviews'))
                    ->importer(ReviewImporter::class)
                    ->job(ImportCsv::class)
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label(__('site.bulk_delete')),

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->label(__('site.approve_selected'))
                        ->action(fn(Collection $records) => $records->each->update(['approved' => true]))
                        ->icon('heroicon-o-check'),

                    Tables\Actions\BulkAction::make('unapprove')
                        ->label(__('site.unapprove_selected'))
                        ->action(fn(Collection $records) => $records->each->update(['approved' => false]))
                        ->icon('heroicon-o-x-mark'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereNull('viewed_at')->count();
        return $count > 0 ? (string) $count : null;
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'view' => Pages\ViewReview::route('/{record}'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
