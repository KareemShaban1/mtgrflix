<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Order;
use App\Models\Status;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use App\Notifications\UserNotification;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\OrderResource;
use Filament\Tables\Columns\SelectColumn;
use App\Services\MyFatoorahPaymentService;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderResource::getEloquentQuery()
            )
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn(Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
            ->columns([
                TextColumn::make('number')
                    ->label(__('filament.number'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label(__('filament.customer'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.mobile')
                    ->label(__('filament.mobile'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable()
                    ->label(__('filament.grand_total'))
                    ->searchable(),

                SelectColumn::make('status_id')
                    ->label(__('filament.status'))
                    ->options(Status::all()->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->sortable()
                    ->afterStateUpdated(function ($state, Order $record) {
                        if ($state == 3) {
                            $this->handleStatusThree($record);
                        }
                    }),

                TextColumn::make('created_at')
                    ->label(__('filament.order_date'))
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }


   


    public function handleStatusThree(Order $order)
    {
        $myFatorah = new MyFatoorahPaymentService();
        $user = $order->user;

        foreach ($order->items as $item) {
            $product = $item->product;

            // Check if product is digital and item has no assigned code yet
            if (!empty($product) && $product->type === 'digital' && empty($item->product_code_id)) {
                $quantity = $item->quantity;

                // Get available unused codes for this product
                $codes = $product->codes()->whereNull('used_at')->take($quantity)->get();

                if ($codes->count() == $quantity) {
                    foreach ($codes as $code) {
                        // Assign the code to the item and mark as used
                        $item->update(['product_code_id' => $code->id]);

                        $code->update([
                            'used_at' => now(),
                            'user_id' => $user->id,
                        ]);

                        \Log::info("Assigned code ID {$code->id} to item ID {$item->id}");

                        // Send payment code message
                        $myFatorah->sendPaymentCodeMessage($user, $order, $product, $code);
                    }
                } else {
                    \Log::error('Not enough available codes for product', [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                    ]);

                    DB::rollBack();

                    return response()->json([
                        'error' => 'Not enough available codes for the digital product.'
                    ], 400);
                }
            }
        }

        // Update order payment status and other fields once codes are assigned
        $qr = $myFatorah->qr_code($order);
        $order->update([
            'payment_status' => 'paid',
            'status_id' => 3,
            'qr_code' => $qr,
        ]);

        // Notify user about successful processing
        $user->notify(new UserNotification($order->id, 'تم تنفيذ طلبك بنجاح'));

        return response()->json(['success' => true]);
    }
}
