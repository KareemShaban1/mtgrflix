<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use App\Models\OrderItem;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Grid;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\OrderResource;
use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            \Filament\Actions\ActionGroup::make([
                Actions\Action::make('viewCustomer')
                    ->label(__('filament.view_customer'))
                    ->icon('heroicon-o-eye')
                    ->url(fn () => \App\Filament\Resources\UserResource::getUrl('view', ['record' => $this->record->user_id]))
                    ->openUrlInNewTab(),

                Actions\Action::make('banCustomer')
                    ->label(__('filament.ban_customer'))
                    ->icon('heroicon-o-no-symbol')
                    ->requiresConfirmation()
                    ->modalHeading(__('filament.ban_customer_confirmation'))
                    ->modalDescription(__('filament.are_you_sure_ban_customer'))
                    ->action(function () {
                        $this->record->user->update(['is_active' => false]);
                    })
                    ->hidden(fn () => !$this->record->user_id || $this->record->user?->is_active === false),
            ])
            ->label(__('filament.customer_options'))
            ->icon('heroicon-o-user')
            ->button()
            ->color('primary')
            ->dropdownPlacement('bottom-end'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Order Information Section
                Section::make(__('filament.order_information'))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('number')
                                    ->label(__('filament.order_id')),

                                TextEntry::make('user.name')
                                    ->label(__('filament.customer')),

                                TextEntry::make('created_at')
                                    ->label(__('filament.order_date'))
                                    ->formatStateUsing(function ($state) {
                                        return $state->locale('en')->translatedFormat('l j F Y | h:i A');
                                    }),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('grand_total')
                                    ->label(__('filament.grand_total'))
                                    ->money('SAR'),

                                    TextEntry::make('status_id') // Use the relationship column name
                                    ->label(__('filament.status'))
                                    ->badge()
                                    ->formatStateUsing(function ($state) {
                                        // Get the status name based on ID from your seeder
                                        $statuses = [
                                            1 => __('Pending'), // or __('filament.status.pending')
                                            2 => __('Shipped'),
                                            3 => __('Completed'),
                                            4 => __('Cancelled'),
                                            5 => __('Declined'),
                                        ];

                                        return $statuses[$state] ?? __('Unknown');
                                    })
                                    ->color(function ($state) {
                                        return match ($state) {
                                            1 => 'info',       // Pending
                                            2 => 'warning',    // Shipped
                                            3 => 'success',    // Completed
                                            4 => 'danger',     // Cancelled
                                            5 => 'danger',     // Declined
                                            default => 'gray',
                                        };
                                    }),
                            ]),
                    ])->columnSpan(2),

                    // Customer Information Section
                    Section::make(__('filament.customer_information'))
                    ->schema([
                        Grid::make()
                            ->columns([
                                'default' => 1,
                                'sm' => 2,
                                'lg' => 3,
                            ])
                            ->schema([
                                // 1. Customer Name
                                TextEntry::make('user.name')
                                    ->label(__('filament.customer')),

                                // 2. Phone Number
                                TextEntry::make('user.phone')
                                    ->label(__('site.phone')),

                                // 3. WhatsApp Icon Link
                                TextEntry::make('user.mobile')
                                    ->label(__('filament.whatsapp'))
                                    ->formatStateUsing(function (?string $state) {
                                        if (!$state) return null;

                                        $phone = preg_replace('/[^0-9]/', '', $state);
                                        $whatsappUrl = "https://wa.me/{$phone}";

                                        return "<a href='{$whatsappUrl}' target='_blank' class='text-primary-500 hover:underline'>{$state}</a>";
                                    })
                                    ->html(),

                                // 4. Email
                                TextEntry::make('user.email')
                                    ->label(__('site.email')),
                            ]),
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'md' => 1,
                    ])
                    ->extraAttributes([
                        'class' => 'h-auto md:h-[310px]',
                    ]),

                // Payment Information Section
                Section::make(__('filament.payment_information'))
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextEntry::make('payment.paymentMethod')
                                    ->label(__('filament.payment_method')),

                                TextEntry::make('payment_status')
                                    ->label(__('filament.payment_status'))
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'paid' => 'success',
                                        'pending' => 'warning',
                                        'failed' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),
                    ])
                    ->columnSpan(1)
                    ->extraAttributes(['style' => 'height: 250px;']),

                // Order Items Section
                Section::make(__('filament.order_items'))
                    ->schema([
                        \Filament\Infolists\Components\RepeatableEntry::make('items')
                        ->label(__('filament.items'))
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label(__('filament.product_name')),

                                TextEntry::make('quantity')
                                    ->label(__('filament.quantity'))
                                    ->numeric(),

                                TextEntry::make('unit_amount')
                                    ->label(__('filament.unit_price'))
                                    ->money('SAR'),

                                TextEntry::make('total_amount')
                                    ->label(__('filament.total_price'))
                                    ->money('SAR'),
                                TextEntry::make('selectedCode.code')
                                    ->label(__('filament.selected_code'))
                                    // ->formatStateUsing(fn ($state) => strip_tags($state, '<strong><em><a>'))
                                    ->html()
                                    ->visible(function ($record) {
                                        return $record->product->type === 'digital'; // Only show for digital products
                                    })
                                // TextEntry::make('options')
                                //     ->label(__('filament.options'))
                                //     ->listWithLineBreaks()
                                //     ->state(function (OrderItem $record) {
                                //         return $record->options?->map(function ($option) {
                                //             return "{$option->name}: {$option->value}";
                                //         })->toArray() ?? [];
                                //     })
                                //     ->hidden(fn ($state) => empty($state))
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                    ])
                    ->columnSpan(2),
            ])
            ->columns(3);
    }
}
