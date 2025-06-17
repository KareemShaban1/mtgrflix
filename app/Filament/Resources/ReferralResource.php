<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferralResource\Pages;
use App\Models\Referral;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class ReferralResource extends Resource
{
    protected static ?string $model = Referral::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';
    protected static ?string $navigationGroup = 'Marketing'; // Optional
    protected static ?string $label = 'Referral';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('ref_id')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            Forms\Components\TextInput::make('visits_count')
                ->numeric()
                ->default(0),

            Forms\Components\TextInput::make('purchases_count')
                ->numeric()
                ->default(0),

            Forms\Components\TextInput::make('total_sales')
                ->numeric()
                ->default(0.00)
                ->prefix('$'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('ref_id')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('visits_count')->sortable(),
            Tables\Columns\TextColumn::make('purchases_count')->sortable(),
            Tables\Columns\TextColumn::make('total_sales')->money('USD')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferrals::route('/'),
            'create' => Pages\CreateReferral::route('/create'),
            'edit' => Pages\EditReferral::route('/{record}/edit'),
        ];
    }
}

