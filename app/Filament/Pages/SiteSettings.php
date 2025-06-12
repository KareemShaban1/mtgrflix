<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Spatie\Sitemap\SitemapGenerator;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\ButtonAction;
use App\Filament\Pages\Settings\Site_Settings;
use Filament\Forms\Components\FileUpload;
use TomatoPHP\FilamentSettingsHub\Models\Setting;
use TomatoPHP\FilamentSettingsHub\Traits\UseShield;
use TomatoPHP\FilamentSettingsHub\Settings\SitesSettings;

class SiteSettings extends SettingsPage
{
    use UseShield;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = Site_Settings::class;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return trans('site.settings');
    }

    protected function getActions(): array
    {
        $tenant = \Filament\Facades\Filament::getTenant();
        if ($tenant) {
            return [
                Action::make('sitemap')
                    ->requiresConfirmation()
                    ->action(fn() => $this->generateSitemap())
                    ->label(trans('site.site-map')),
                Action::make('back')->action(fn() => redirect()->route('filament.' . filament()->getCurrentPanel()->getId() . '.pages.settings-hub', $tenant))->color('danger')->label(trans('filament-settings-hub::messages.back')),
            ];
        }

        return [
            Action::make('sitemap')
                ->requiresConfirmation()
                ->action(fn() => $this->generateSitemap())
                ->label(trans('site.site-map')),
            Action::make('back')->action(fn() => redirect()->route('filament.' . filament()->getCurrentPanel()->getId() . '.pages.settings-hub'))->color('danger')->label(trans('filament-settings-hub::messages.back')),
        ];
    }


    public function generateSitemap()
    {
        SitemapGenerator::create(config('app.url'))->writeToFile(public_path('sitemap.xml'));

        Notification::make()
            ->title(trans('site.site-map-notification'))
            ->icon('heroicon-o-check-circle')
            ->iconColor('success')
            ->send();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 1])->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tab::make(__('site.general'))
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('site_name')
                                        ->label(trans('site.site_name'))
                                        ->columnSpan(2)
                                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("site_name")' : null),

                                    TextArea::make('site_description_en')
                                        ->label(trans('site.site_description_en'))
                                        ->columnSpan(2)
                                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("site_description")' : null),
                                        TextArea::make('site_description_ar')
                                        ->label(trans('site.site_description_ar'))
                                        ->columnSpan(2)
                                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("site_description")' : null),
                                    TextArea::make('site_keywords')
                                        ->label(trans('site.site_keywords'))
                                        ->columnSpan(2)
                                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("site_keywords")' : null),
                                ])
                            ]),

                        Tab::make(__('site.media'))
                            ->schema([
                                Grid::make(2)->schema([
                                    FileUpload::make('site_profile')
                                        ->label(trans('site.site_profile'))
                                        ->columnSpan(2)
                                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("site_profile")' : null),

                                    FileUpload::make('site_logo')
                                        ->label(trans('site.site_logo'))
                                        ->columnSpan(2)
                                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("site_logo")' : null),
                                ])
                            ]),

                        Tab::make(__('site.contact'))
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('site_phone')
                                        ->label(trans('site.site_phone'))
                                        ->columnSpan(2)
                                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("site_phone")' : null),

                                    TextInput::make('site_author')
                                        ->label(trans('site.site_author'))
                                        ->columnSpan(2)
                                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("site_author")' : null),

                                    TextInput::make('site_email')
                                        ->label(trans('site.site_email'))
                                        ->columnSpan(2)
                                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("site_email")' : null),
                                ])
                            ]),

                        Tab::make(__('site.whatsapp_templates'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Textarea::make('whatsapp_template_5_star_review')
                                            ->label(__('site.whatsapp_5_star'))
                                            ->columnSpanFull()
                                            ->rows(5)
                                            ->default(fn() => Setting::where('name', 'whatsapp_template_5_star_review')->first()?->payload)
                                            ->hint(__('site.whatsapp_code_hint')),

                                        Textarea::make('whatsapp_template_4_star_review')
                                            ->label(__('site.whatsapp_4_star'))
                                            ->columnSpanFull()
                                            ->rows(5)
                                            ->default(fn() => Setting::where('name', 'whatsapp_template_4_star_review')->first()?->payload)
                                            ->hint(__('site.whatsapp_code_hint')),

                                        Textarea::make('whatsapp_template_1_star_review')
                                            ->label(__('site.whatsapp_1_star'))
                                            ->columnSpanFull()
                                            ->rows(5)
                                            ->default(fn() => Setting::where('name', 'whatsapp_template_1_star_review')->first()?->payload)
                                            ->hint(__('site.whatsapp_no_placeholder')),

                                        Textarea::make('whatsapp_template_abandoned_cart')
                                            ->label(__('site.whatsapp_abandoned_cart'))
                                            ->columnSpanFull()
                                            ->rows(5)
                                            ->default(fn() => Setting::where('name', 'whatsapp_template_abandoned_cart')->first()?->payload)
                                            ->hint(__('site.whatsapp_abandoned_hint')),

                                        Textarea::make('whatsapp_template_order_delivered')
                                            ->label(__('site.whatsapp_order_delivered'))
                                            ->columnSpanFull()
                                            ->rows(5)
                                            ->default(fn() => Setting::where('name', 'whatsapp_template_order_delivered')->first()?->payload)
                                            ->hint(__('site.whatsapp_delivered_hint')),

                                        Textarea::make('whatsapp_template_review_request')
                                            ->label(__('site.whatsapp_review_request'))
                                            ->columnSpanFull()
                                            ->rows(5)
                                            ->default(fn() => Setting::where('name', 'whatsapp_template_review_request')->first()?->payload)
                                            ->hint(__('site.whatsapp_review_hint')),
                                    ])
                            ]),


                    ]),


            ]),
        ];
    }
}
