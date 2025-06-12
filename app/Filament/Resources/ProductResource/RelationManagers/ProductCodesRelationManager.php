<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Resources\RelationManagers\RelationManager;

class ProductCodesRelationManager extends RelationManager
{
    protected static string $relationship = 'productCodes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('code')
                    ->label(__('site.code'))
                    ->required()
                    ->columnSpanFull(),
                    
                
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->type == 'digital';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('site.code'))
                    ->formatStateUsing(fn (string $state): string => strip_tags($state))
                    ->html(false)
                    ->wrap()
                    ->searchable(),
                    
                // Tables\Columns\IconColumn::make('is_active')
                //     ->label(__('site.active'))
                //     ->boolean(),
                    
                Tables\Columns\TextColumn::make('used_at')
                    ->label(__('site.used_on'))
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('site.used_by'))
                    ->placeholder(__('site.not_used_yet')),
            ])
            ->filters([
                Tables\Filters\Filter::make('used')
                    ->label(__('site.used_codes'))
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('used_at')),
                    
                Tables\Filters\Filter::make('unused')
                    ->label(__('site.unused_codes'))
                    ->query(fn (Builder $query): Builder => $query->whereNull('used_at')),
                    
              
                    
               
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('create')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('site.edit')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('bulk_delete')),
                    
                ]),
            ]);
    }
}