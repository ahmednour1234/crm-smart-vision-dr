<?php

namespace App\Providers\Filament;

use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;

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

            // ✅ حقن الـ Avatar في زر المستخدم نفسه (Topbar trigger)
            ->renderHook(
                PanelsRenderHook::USER_MENU_TRIGGER_BEFORE,
                fn () => view('filament.components.initials-avatar')
            )

            // (اختياري) لو عايز تحقنه داخل الـ dropdown قبل العناصر
            // ->renderHook(
            //     PanelsRenderHook::USER_MENU_BEFORE,
            //     fn () => view('filament.components.initials-avatar')
            // )

            ->userMenuItems([
                MenuItem::make()
                    ->label('ملفي الشخصي')
                    ->icon('heroicon-o-user-circle')
                    ->url('#'),

                MenuItem::make()
                    ->label('تسجيل الخروج')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    // الأفضل تستخدم route لو متاح عندك، بدل URL ثابت
                    ->url(fn (): string => url('/admin/logout')),
            ])

            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets');
    }
}
