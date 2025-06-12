<?php

namespace App\Filament\Imports;

use App\Models\Review;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ReviewImporter extends Importer
{
    protected static ?string $model = Review::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('client_name')
                ->label('client_id')
                // ->rules(['string', 'nullable'])
                ,



            ImportColumn::make('review')
                // ->rules(['string'])
                ,

            ImportColumn::make('rating')
            ,

            ImportColumn::make('type'),


        ];
    }

    public function resolveRecord(): ?Review
    {
        // return Review::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Review();
    }
    protected function beforeSave(): void
    {
        $this->record->approved = 1;
        $this->record->product_id = 12;
    }
    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your review import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
