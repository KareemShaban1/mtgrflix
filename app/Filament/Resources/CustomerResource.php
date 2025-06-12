<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CustomerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers;
use Spatie\Permission\Models\Role;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'mobile';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public static function getNavigationLabel(): string
    {
        return __('site.all_users');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')

                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->maxlength(255)
                    ->unique(ignoreRecord: true)
                    ->required(),

                Forms\Components\Select::make('roles')
                    ->label('User Roles')
                    ->options(Role::all()->pluck('name', 'id'))
                    ->preload()
                    ->searchable()
                    ->required()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (Forms\Set $set, ?User $record) {
                        if ($record) {
                            $set('roles', $record->roles->pluck('id')->toArray());
                        }
                    })
                    ->saveRelationshipsUsing(function (User $record, $state) {
                        // Ensure $state is always an array
                        $roleIds = is_array($state) ? $state : [$state];

                        // Filter out null/empty values
                        $roleIds = array_filter($roleIds, fn($id) => !empty($id));

                        // Get role names and sync
                        $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
                        $record->syncRoles($roles);

                        // Update is_admin flag
                        $record->update(['is_admin' => !empty($roles)]);
                    }),

                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At')
                    ->default(now()),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord),

                Forms\Components\TextInput::make('mobile')
                    ->numeric()
                    ->hidden(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->separator(', '),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),


                ])
            ])
            ->headerActions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
        return parent::getEloquentQuery()->where('is_admin', true);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        do {
            // Generate a random 10-digit mobile number
            $mobile = mt_rand(1000000000, 9999999999);
        } while (User::where('mobile', $mobile)->exists());

        $data['mobile'] = $mobile;

        return $data;
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
