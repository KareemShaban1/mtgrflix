<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Slider;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SliderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SliderResource\RelationManagers;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getNavigationLabel(): string
    {
        return __('site.sliders');
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            FileUpload::make('image')
                ->label(__('site.image'))
                ->image()
                ->required()
                ->directory('sliders')
                ->columnSpanFull(),

            Forms\Components\Hidden::make('type')
                ->default('slider'),

            Forms\Components\TextInput::make('url')
                ->label(__('site.url'))
                ->nullable()
                ->url()
                ->maxLength(255)
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label(__('site.is_active'))
                ->default(true),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('site.image'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('url')
                    ->label(__('site.url'))
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('is_active')
                    ->label(__('site.is_active'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('site.created_at'))
                    ->dateTime(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
