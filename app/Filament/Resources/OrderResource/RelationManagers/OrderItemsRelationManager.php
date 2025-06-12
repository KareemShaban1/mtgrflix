<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\RelationManagers\RelationManager;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items'; // انتبه لازم تكون العلاقة اسمها 'items' في الـ Order model

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('product_id')
                    ->required()
                    ->numeric(),

                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1),

                TextInput::make('unit_amount')
                    ->numeric()
                    ->step(0.01),

                TextInput::make('total_amount')
                    ->numeric()
                    ->step(0.01),

                Textarea::make('options')
                    ->nullable()
                    ->columnSpanFull(),

                TextInput::make('product_code_id')
                    ->nullable()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product Name')
                    ->searchable(),

                TextColumn::make('quantity')
                    ->sortable(),

                TextColumn::make('unit_amount')
                    ->money('SAR') // أو عملتك مثلاً USD

                ,

                TextColumn::make('total_amount')
                    ->money('SAR'),

                TextColumn::make('options')
                    ->limit(30),

                TextColumn::make('productCode.code')
                    ->label('Product Code')
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
