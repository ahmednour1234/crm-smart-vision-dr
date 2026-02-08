<?php

namespace App\Providers\Filament;

use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;

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
                    ->label('Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url('#'),
                MenuItem::make()
                    ->label('Logout')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->action(function () {
                        Auth::logout();
                        request()->session()->invalidate();
                        request()->session()->regenerateToken();
                        return redirect('/employee/login');
                    }),
            ])
            ->discoverResources(in: app_path('Filament/Employee/Resources'), for: 'App\\Filament\\Employee\\Resources')
            ->discoverPages(in: app_path('Filament/Employee/Pages'), for: 'App\\Filament\\Employee\\Pages')
            ->discoverWidgets(in: app_path('Filament/Employee/Widgets'), for: 'App\\Filament\\Employee\\Widgets');
    }
}
