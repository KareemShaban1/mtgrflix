<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Page;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PageResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\PageResource\RelationManagers;
use Mvenghaus\FilamentPluginTranslatableInline\Forms\Components\TranslatableContainer;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 7;

    public static function getNavigationLabel(): string
    {
        return __('site.pages');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
              

                        TranslatableContainer::make(
                            TextInput::make('title')

                                // ->required()
                                ->live(onBlur: true)


                            // ->columns(2)
                        )->columnSpanFull(),
                        Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(191),
                        TranslatableContainer::make(
                            Forms\Components\RichEditor::make('content')
                                ->columnSpanFull(),
                        )->columnSpanFull(),
                        // Forms\Components\Select::make('status')
                        //     ->required()
                        //     ->options(Page::getStatuses())
                        //     // ->default(Page::STATUS_DRAFT)
                        //     ,
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
