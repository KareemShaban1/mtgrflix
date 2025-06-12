<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Filament\Resources\ReportResource\Widgets\SalesSummary;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.reports');
    }

    protected static ?string $modelLabel = 'Reports';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->paginated(false)
            ->actions([])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //
                // ]),
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
            'index' => Pages\ListReports::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('payment_status', 'paid');
    }
}
