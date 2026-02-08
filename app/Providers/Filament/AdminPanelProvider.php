<?php

namespace App\Providers\Filament;

use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->darkMode()
            ->colors([
                'primary' => Color::Blue,
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
                        return redirect('/admin/login');
                    }),
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets');
    }
}
