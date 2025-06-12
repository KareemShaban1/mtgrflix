<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Status;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\OrderResource;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('number')
            ->recordUrl(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
            ->columns([
                TextColumn::make('number')
                ->label('Order Number')
                ->searchable(),

                TextColumn::make('grand_total')
                ->money('IDR'),

                SelectColumn::make('status_id')
                ->label(__('filament.status'))
                ->options(Status::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->sortable(),

                TextColumn::make('status.name')
                ->icon(fn (string $state): string => match(strtolower($state)) {
                    'new' => 'heroicon-m-sparkles',
                    'processing' => 'heroicon-m-arrow-path',
                    'shipped' => 'heroicon-m-truck',
                    'delivered' => 'heroicon-m-check-badge',
                    'cancelled' => 'heroicon-m-x-circle',
                    default => 'heroicon-m-question-mark-circle', // default icon for unknown statuses
                })
                ->sortable(),

                TextColumn::make('payment_method')
                ->sortable()
                ->searchable(),

                TextColumn::make('payment_status')
                ->sortable()
                ->badge()
                ->searchable(),

                TextColumn::make('created_at')
                ->label('Order Date')
                ->dateTime()

            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
