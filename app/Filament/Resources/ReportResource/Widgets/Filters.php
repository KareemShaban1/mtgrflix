<?php

namespace App\Filament\Resources\ReportResource\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Widget;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class Filters extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.resources.report-resource.widgets.filters';
    protected int|string|array $columnSpan = 'full';
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'period' => 'all_time',
            'to' => now()->format('Y-m-d'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Grid::make(3) // Changed from 2 to 3 columns
                    ->schema([
                        Select::make('period')
                            ->label(__('filament.time_period'))
                            ->options([
                                'today' => __('filament.today'),
                                'yesterday' => __('filament.yesterday'),
                                'last_week' => __('filament.last_week'),
                                'last_month' => __('filament.last_month'),
                                'this_month' => __('filament.this_month'),
                                'this_year' => __('filament.this_year'),
                                'last_year' => __('filament.last_year'),
                                'all_time' => __('filament.all_time'),
                            ])
                            ->required()
                            ->default('all_time')
                            ->afterStateUpdated(function ($state, $set) {
                                $dates = $this->getDateRangeForPeriod($state);
                                $set('from', $dates['from']);
                                $set('to', $dates['to']);
                            }),

                        DatePicker::make('from')
                            ->label(__('filament.from'))
                            ->native(true)
                            ->displayFormat('d/m/Y')
                            ->closeOnDateSelection(),

                        DatePicker::make('to')
                            ->label(__('filament.to'))
                            ->native(true)
                            ->displayFormat('d/m/Y')
                            ->closeOnDateSelection()
                            ->default(now()),
                    ]),
            ]);
    }

    protected function getDateRangeForPeriod(string $period): array
    {
        return match ($period) {
            'today' => [
                'from' => now()->format('Y-m-d'),
                'to' => now()->format('Y-m-d'),
            ],
            'yesterday' => [
                'from' => now()->subDay()->format('Y-m-d'),
                'to' => now()->subDay()->format('Y-m-d'),
            ],
            'last_week' => [
                'from' => now()->startOfWeek(Carbon::SATURDAY)->format('Y-m-d'),
                'to' => now()->endOfWeek(Carbon::FRIDAY)->format('Y-m-d'),
            ],
            'last_month' => [
                'from' => now()->subMonth()->startOfMonth()->format('Y-m-d'),
                'to' => now()->subMonth()->endOfMonth()->format('Y-m-d'),
            ],
            'this_month' => [
                'from' => now()->startOfMonth()->format('Y-m-d'),
                'to' => now()->format('Y-m-d'),
            ],
            'this_year' => [
                'from' => now()->startOfYear()->format('Y-m-d'),
                'to' => now()->format('Y-m-d'),
            ],
            'last_year' => [
                'from' => now()->subYear()->startOfYear()->format('Y-m-d'),
                'to' => now()->subYear()->endOfYear()->format('Y-m-d'),
            ],
            default => [ // all_time
                'from' => null,
                'to' => now()->format('Y-m-d'),
            ],
        };
    }

    public function applyFilters(): void
    {
        $state = $this->form->getState();

        $this->dispatch('updateFilters', filters: [
            'from' => $state['from'] ? Carbon::parse($state['from'])->format('Y-m-d') : null,
            'to' => $state['to'] ? Carbon::parse($state['to'])->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
        ]);
    }
    public function resetFilters(): void
    {
        $this->form->fill([
            'period' => 'today',
            'from' => now()->format('Y-m-d'),
            'to' => now()->format('Y-m-d'),
        ]);
        $this->dispatch('updateFilters', filters: []);
    }
}
