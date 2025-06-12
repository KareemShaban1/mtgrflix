<?php

namespace App\Filament\Imports;

use App\Models\User;
use App\Models\Order;
use App\Models\Currency;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class OrderImporter extends Importer 
{
        use Queueable;

    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [

            ImportColumn::make('user')
                ->relationship(
                    resolveUsing: fn(string $state) => User::where('mobile', $state)->first() ?? User::find(1)
                ),

            ImportColumn::make('grand_total'),

            ImportColumn::make('sub_total'),

            ImportColumn::make('discount'),

            ImportColumn::make('payment_method')
            // ->requiredMapping()
            ,

            ImportColumn::make('status')
            // ->requiredMapping()
            ,

            ImportColumn::make('number'),
            ImportColumn::make('client'),



            ImportColumn::make('created_at'),
            ImportColumn::make('updated_at'),

            ImportColumn::make('product_items'),

            ImportColumn::make('coupon_code'),
            ImportColumn::make('country'),

        ];
    }

    public function resolveRecord(): ?Order
    {
        // if (isset($this->data['number'])) {
        //     return Order::firstOrNew([
        //         'number' => $this->data['number'],
        //     ]);
        // }

        return new Order();
    }

    protected function beforeSave(): void {}

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your order import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
