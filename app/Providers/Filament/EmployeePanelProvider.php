<?php

namespace App\Providers\Filament;

use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\View\View;

class EmployeePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('employee')
            ->path('employee')
            ->login()
            ->darkMode()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->assets([
                Css::make('custom-style', resource_path('css/custom.css')),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('ملفي الشخصي')
                    ->icon('heroicon-o-user-circle')
                    ->url('#'),
                MenuItem::make()
                    ->label('تسجيل الخروج')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->url('/employee/logout'),
            ])
            ->discoverResources(in: app_path('Filament/Employee/Resources'), for: 'App\\Filament\\Employee\\Resources')
            ->discoverPages(in: app_path('Filament/Employee/Pages'), for: 'App\\Filament\\Employee\\Pages')
            ->discoverWidgets(in: app_path('Filament/Employee/Widgets'), for: 'App\\Filament\\Employee\\Widgets');
    }

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::topbar.end',
            fn (): View => view('filament.components.initials-avatar'),
        );
    }
}
