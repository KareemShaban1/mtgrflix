<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Testimonial;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Imports\Jobs\ImportCsv;
use App\Filament\Imports\TestimonialImporter;
use App\Filament\Resources\TestimonialResource\Pages;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('site.testimonials');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user']); // Eager load user relation
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label(__('site.user'))
                    ->required()
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->whereNotNull('name')->limit(50) // limit to avoid memory bloat
                    )
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn (User $user) => $user->display_name)
                    ->columnSpanFull(),

                Textarea::make('comment')
                    ->label(__('site.comment'))
                    ->required()
                    ->maxLength(500)
                    ->columnSpanFull(),

                TextInput::make('rate')
                    ->label(__('site.rating'))
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->nullable(),

                Toggle::make('is_active')
                    ->label(__('site.is_active'))
                    ->default(false)
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('customer')
                    ->label(__('site.user'))
                    ->getStateUsing(function (Testimonial $record) {
                        // If user exists, show user's name/email
                        if ($record->user) {
                            return $record->user->name ?? $record->user->email ?? 'Unknown User';
                        }
                        // Otherwise, show client_name from the review
                        return $record->client_name ?? 'Anonymous';
                    })
                    ->sortable() // Sorting will work on user.name if available
                    ->searchable(), // Search works on both user.name and client_name

                TextColumn::make('comment')
                    ->label(__('site.comment'))
                    ->words(10)
                    // ->tooltip(fn (Testimonial $record): string => \Str::limit($record->comment, 100))
                    ->searchable(),

                TextColumn::make('rate')
                    ->label(__('site.rating'))
                    ->formatStateUsing(fn (?int $state): string => str_repeat('⭐', (int) $state)),

                ToggleColumn::make('is_active')
                    ->label(__('site.is_active')),

                TextColumn::make('created_at')
                    ->label(__('site.created_at'))
                    ->date(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->label(__('site.active_testimonials'))
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
            ])
            ->recordUrl(
                fn (Testimonial $record): string => static::getUrl('view', ['record' => $record]),
            )
            ->actions([
                Tables\Actions\ViewAction::make()->label(__('site.view')),
                Tables\Actions\EditAction::make()->label(__('site.edit')),
                Tables\Actions\DeleteAction::make()->label(__('site.delete')),
            ])
             ->headerActions([
                ImportAction::make()
                    ->label(__('site.import_testimonials'))
                    ->importer(TestimonialImporter::class)
                    ->job(ImportCsv::class)
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label(__('site.bulk_delete')),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'view' => Pages\ViewTestimonial::route('/{record}'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
