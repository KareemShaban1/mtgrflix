<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use Filament\Forms\Components\Hidden;
use Mvenghaus\FilamentPluginTranslatableInline\Forms\Components\TranslatableContainer;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('site.categories');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make()
                            ->schema([
                                TranslatableContainer::make(
                                    TextInput::make('name')
                                        ->label(__('site.name'))
                                        ->required()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (Set $set, Component $component, ?string $state) {
                                            $set('../slug', Str::slug($state));
                                        })
                                )->columnSpanFull(),

                                TextInput::make('slug')
                                    ->label(__('site.slug'))
                                    ->maxlength(255)
                                    ->required()
                                    ->dehydrated()
                                    ->unique(Category::class, 'slug', ignoreRecord: true)
                            ]),

                        FileUpload::make('image')
                            ->label(__('site.image'))
                            ->image()
                            ->directory('categories'),

                        Toggle::make('is_active')
                            ->label(__('site.is_active'))
                            ->required()
                            ->default(true),

                        Select::make('parent_id')
                            ->relationship(
                                name: 'parent',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->whereNull('parent_id')
                            )->getOptionLabelFromRecordUsing(fn($record) => "{$record->getTranslation('name', 'en')} - {$record->getTranslation('name', 'ar')}")
                            ->searchable()
                            ->required()
                            ->label(trans('product.main_category'))
                            ->preload()
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('site.name'))
                    ->searchable(),

                TextColumn::make('slug')
                    ->label(__('site.slug'))
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label(__('site.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label(__('site.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('site.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->label(__('site.view')),
                    EditAction::make()
                        ->label(__('site.edit')),
                    DeleteAction::make()
                        ->label(__('site.delete')),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('site.bulk_delete')),
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
        return parent::getEloquentQuery()->whereNotNull('parent_id');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
