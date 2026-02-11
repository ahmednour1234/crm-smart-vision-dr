<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\MeetingsKpiOverview;
use App\Filament\Admin\Widgets\PermissionsStatsWidget;
use App\Filament\Admin\Widgets\TopAgentsThisWeek;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected function getHeaderWidgets(): array
    {
        return [
            MeetingsKpiOverview::class,
            PermissionsStatsWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            TopAgentsThisWeek::class,
        ];
    }
}
