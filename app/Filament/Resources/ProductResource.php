<?php

namespace App\Filament\Resources;

use App\Models\Tag;
use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Category;
use Filament\Forms\Form;
use App\Models\FormField;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use Mvenghaus\FilamentPluginTranslatableInline\Forms\Components\TranslatableContainer;
use App\Filament\Resources\ProductResource\RelationManagers\ProductCodesRelationManager;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('site.products');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(trans('product.details'))
                            ->schema([
                                TranslatableContainer::make(
                                    TextInput::make('name')
                                        ->label(trans('product.name'))
                                        ->required()
                                        ->live(onBlur: true)

                                        ->afterStateUpdated(function (Set $set, Component $component, ?string $state) {
                                            $set('../slug', Str::slug($state));
                                        })
                                    // ->columns(2)
                                )->columnSpanFull(),

                                Forms\Components\TextInput::make('slug')
                                    ->maxlength(255)
                                    ->required()
                                    ->dehydrated()
                                    ->unique(Product::class, 'slug', ignoreRecord: true),

                                Forms\Components\TextInput::make('sku')
                                    ->default(uniqid())
                                    ->label(trans('product.sku'))
                                    ->unique(Product::class, column: 'sku', ignoreRecord: true)
                                    ->maxLength(255),
                                TranslatableContainer::make(
                                    TextInput::make('sub_title')
                                        ->label(trans('product.sub_title'))
                                        ->required()

                                    // ->columns(2)
                                )->columnSpanFull(),
                                Forms\Components\Toggle::make('is_active')
                                    ->columnSpanFull()
                                    ->label(trans('product.is_activated')),
                                Forms\Components\Toggle::make('is_featured')
                                    ->columnSpanFull()
                                    ->label(trans('product.is_featured')),

                                FileUpload::make('images')
                                    ->image()
                                    ->columnSpanFull()
                                    ->required()
                                    ->label(trans('product.images'))
                                    ->directory('products'),

                                Select::make('category_id')
                                    ->relationship(name: 'category', titleAttribute: 'name')
                                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->getTranslation('name', 'en')} - {$record->getTranslation('name', 'ar')}")
                                    ->searchable()
                                    ->required()
                                    ->label(trans('product.category'))
                                    ->preload()
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make(trans('product.price'))
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label(trans('product.price'))
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0),

                                Forms\Components\TextInput::make('promotional_price')
                                    ->label(trans('product.promotional_price'))
                                    ->numeric()
                                    ->prefix('$'),
                            ]),




                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([

                                TinyEditor::make('description_seo')
                                    ->label('SEO Description (Meta Description)')
                                    ->helperText('Optimal length: 50-160 characters for search engines')
                                    ->rtl()
                                    ->columnSpanFull(),


                                TinyEditor::make('description_en')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsVisibility('public')
                                    ->fileAttachmentsDirectory('uploads')
                                    ->profile('default')
                                    ->rtl()
                                    ->columnSpanFull()
                                // ->required()

                                ,
                                TinyEditor::make('description_ar')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsVisibility('public')
                                    ->fileAttachmentsDirectory('uploads')
                                    ->profile('default')
                                    ->rtl()
                                    ->columnSpanFull()
                                // ->required()

                                ,

                                // Forms\Components\RichEditor::make('details')
                                //     ->columnSpanFull()
                                //     ->label(trans('product.details')),

                                // Forms\Components\Textarea::make('keywords')
                                //     ->columnSpanFull()
                                //     ->label(trans('product.keywords')),

                                TagsInput::make('tags')
                                    ->suggestions([
                                        'TikTok',
                                        'Instagram',
                                        'YouTube',
                                        'Social Media',
                                        'Marketing',
                                        'SEO',
                                        'Content Creation',
                                        'Influencer',
                                        'Branding',
                                        'Video Production',
                                        'Live Streaming',
                                        'TikTok Likes',
                                        'Instagram Likes',
                                        'TikTok Views',
                                        'Instagram Views',
                                        'Digital Marketing',
                                        'Social Media Strategy',
                                        'Online Presence',
                                    ])
                                    ->columnSpanFull()

                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make(trans('product.options'))
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->columnSpanFull()
                                    ->searchable()
                                    // ->required()
                                    ->live()
                                    ->options([
                                        'digital' => trans('product.digital'),
                                        'service' => trans('product.service'),
                                    ])
                                    ->label(trans('product.type'))
                                // ->default('product')

                                ,


                                Forms\Components\Repeater::make('productCodes')
                                    ->relationship(
                                        'productCodes',
                                        fn(Builder $query) => $query->whereNull('used_at')
                                    )->visible(fn(Forms\Get $get) => in_array($get('type'), ['digital']))
                                    ->label(trans('product.codes'))
                                    ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                                    ->collapsed(false)
                                    ->collapsible()
                                    ->cloneable()
                                    ->schema([

                                        Forms\Components\RichEditor::make('code')
                                            ->label(trans('product.value'))
                                            ->columnSpanFull()
                                            ->hidden(fn($record) => $record?->used_at !== null)
                                            ->extraInputAttributes(['class' => 'max-h-96', 'style' => 'overflow-y: scroll;'])
                                    ]),


                                Forms\Components\Repeater::make('productFields')
                                    ->relationship()

                                    ->visible(fn(Forms\Get $get) => $get('type') === 'service')  // Only show if 'service' is selected
                                    ->label(trans('product.fields'))
                                    // ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                                    ->collapsed()
                                    ->collapsible()
                                    ->cloneable()
                                    ->schema([
                                        // Select Input Type
                                        Forms\Components\Select::make('input_type')
                                            ->label(trans('product.input_type')) // translation key
                                            // ->required()
                                            ->live()
                                            ->options([
                                                'text' => trans('product.types_text'),
                                                // 'textarea' => trans('product.types_textarea'),
                                                'select' => trans('product.types_select'),
                                            ])
                                            ->reactive()
                                            ->afterStateUpdated(fn(callable $set) => $set('options', null)),
                                        TranslatableContainer::make(
                                            TextInput::make('name')
                                                ->label(trans('product.name'))
                                                ->required()
                                                ->live(onBlur: true)

                                        )
                                        //-> visible(fn(Forms\Get $get) => in_array($get('input_type'), ['text', 'textarea']))

                                        ,

                                        // Toggle Required (Visible for 'select' input type)
                                        // Forms\Components\Toggle::make('required')
                                        //     // ->visible(fn(Forms\Get $get) => $get('input_type') === 'select')
                                        //     ->label(trans('product.required')),

                                        // Toggle Multiple (Visible for 'select' input type)
                                        Forms\Components\Toggle::make('multiple')
                                            ->visible(fn(Forms\Get $get) => $get('input_type') === 'select')
                                            ->label(trans('product.multiple')),

                                        // Repeater for Select, Checkbox, Radio Options
                                        Forms\Components\Repeater::make('options')
                                            ->visible(fn(Forms\Get $get) => in_array($get('input_type'), ['select', 'checkbox', 'radio']))
                                            ->label(trans('product.values'))
                                            ->schema([
                                                Forms\Components\TextInput::make('key')
                                                    ->label(trans('product.key'))
                                                    ->required(),
                                                Forms\Components\TextInput::make('value')
                                                    ->label(trans('product.value'))
                                                    ->required(),



                                            ])
                                            ->default([])
                                        // ->columns(2)

                                        ,

                                    ])
                                // ->columns(4)
                                ,


                            ]),


                    ])->columnSpanFull(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('category.name')
                    ->sortable(),

                // TextColumn::make('brand.name')
                //     ->sortable(),

                TextColumn::make('stock_count')
                    ->label('Stock')
                    ->sortable(),

                TextColumn::make('stock_count')
                    ->label('Stock')
                    ->sortable(),

                TextColumn::make('price')
                    // ->money('IDR')
                    ->sortable(),



                ToggleColumn::make('is_active')
                    ->label(__('site.is_active')),

                TextColumn::make('created_at')
                    ->datetime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('Category')
                    ->relationship('category', 'name'),


            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
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
            // ProductCodesRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount([
                'productCodes as stock_count' => function ($query) {
                    $query->whereNull('used_at');
                }
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
