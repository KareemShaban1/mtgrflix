<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Imports\UserImporter;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Imports\Jobs\ImportCsv;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;
use Illuminate\Database\Eloquent\Model; // Import Model class

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'mobile';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('site.all_customers');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('site.personal_information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('site.name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label(__('site.email'))
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->required(),

                        Forms\Components\TextInput::make('mobile')
                            ->label(__('site.mobile'))
                            ->maxLength(20),

                        Forms\Components\Toggle::make('is_active')
                            ->label(__('site.is_active'))
                            ->inline(false)
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('site.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('site.email'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mobile')
                    ->label(__('site.mobile'))
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('site.active'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('orders_count')
                    ->label(__('site.orders_count'))
                    ->counts('orders')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('site.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->label(__('site.active_users'))
                    ->query(fn(Builder $query) => $query->where('is_active', true)),

                Tables\Filters\Filter::make('created_at')
                    ->label(__('site.created_at'))
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('site.created_from')),
                        Forms\Components\DatePicker::make('created_until')
                            ->label(__('site.created_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date) => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date) => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->label(__('site.import_users'))
                    ->importer(UserImporter::class)
                    ->job(ImportCsv::class)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('site.delete_selected')),
                    Tables\Actions\BulkAction::make('markVerified')
                        ->label(__('site.mark_as_verified'))
                        ->icon('heroicon-o-check')
                        ->action(function ($records) {
                            $records->each->update(['email_verified_at' => now()]);
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_admin', false);
    }

    // Add this method to customize the global search result URL
    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return static::getUrl('view', ['record' => $record]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}