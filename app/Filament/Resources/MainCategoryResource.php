<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\MainCategory;
use Filament\Resources\Resource;
use Google\Service\Transcoder\Input;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MainCategoryResource\Pages;
use App\Filament\Resources\MainCategoryResource\RelationManagers;
use Mvenghaus\FilamentPluginTranslatableInline\Forms\Components\TranslatableContainer;

class MainCategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationLabel(): string
    {
        return __('site.main_categories');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TranslatableContainer::make(
                    TextInput::make('name')
                        ->label(__('site.name'))
                        ->required()
                        ->live(onBlur: true)

                )->columnSpanFull(),
                FileUpload::make('image')
                    ->image()
                    ->directory('main-categories')
                    ->required()
                    ->columnSpanFull()
                    ,

                TextInput::make('link')
                    ->url()
                    ->columnSpanFull()
                    ->nullable(),

                    Hidden::make('name')->default(''),
                    Hidden::make('slug')->default(Str::random(5)),
                    Hidden::make('identifier')->default('iden'),

                    // TextInput::make('slug')->hidden(),
                    // TextInput::make('name')->hidden(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->circular(),
                TextColumn::make('link')->wrap()->copyable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNull('parent_id');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMainCategories::route('/'),
            'create' => Pages\CreateMainCategory::route('/create'),
            'edit' => Pages\EditMainCategory::route('/{record}/edit'),
        ];
    }
}
