<?php

namespace App\Filament\Imports;

use App\Models\Review;
use App\Models\Testimonial;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use PHPUnit\Framework\Attributes\Test;

class TestimonialImporter extends Importer
{
    protected static ?string $model = Testimonial::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('rate')
                // ->numeric()

                ,

            ImportColumn::make('comment')
                ->requiredMapping()


                ,



            ImportColumn::make('client_name'),
        ];
    }

    public function resolveRecord(): ?Testimonial
    {
        // return Review::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Testimonial();
    }
 protected function beforeSave(): void
    {
        $this->record->is_active = 1;
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
