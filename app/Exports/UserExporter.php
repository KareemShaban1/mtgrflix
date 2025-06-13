<?php

namespace App\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Column;
use Filament\Forms\Components\Component;

class UserExporter extends Exporter
{
    public static function getName(): string
    {
        return 'User Export';
    }

    public function getTableQuery(): Builder
    {
        return User::query()->withCount('orders');
    }

    public static function getColumns(): array
{
    return [
        ExportColumn::make('id')
            ->label('id')
            ->formatStateUsing(fn(User $user) => $user->id),

        ExportColumn::make('name')
            ->label('full_name')
            ->formatStateUsing(fn(User $user) => $user->name),

        ExportColumn::make('mobile')
        ->label('mobile')
            ->formatStateUsing(fn(User $user) => $user->mobile ?? 'N/A'),

        ExportColumn::make('email')
        ->label('email')
            ->formatStateUsing(fn(User $user) => $user->email),

        ExportColumn::make('created_at')
            ->label('created_at')
            ->formatStateUsing(fn(User $user) => $user->created_at?->format('Y-m-d H:i:s')),

        ExportColumn::make('city')
        ->label('city')
            ->formatStateUsing(fn(User $user) => $user->city ?? ''),

        ExportColumn::make('gender')
        ->label('gender')
            ->formatStateUsing(fn(User $user) => $user->gender ?? ''),

        ExportColumn::make('birthday')
        ->label('birthday')
            ->formatStateUsing(fn(User $user) => $user->birthday ?? ''),
    ];
}


    public static function getOptionsFormComponents(): array
    {
        return [];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'The user export is complete. You can now download the file.';
    }

    public static function shouldQueue(): bool
    {
        return false;
    }
}
