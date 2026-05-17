<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Company;
use App\Models\Meeting;
use Carbon\CarbonImmutable;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MeetingsKpiOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return Filament::auth()->user()?->role?->slug === 'admin';
    }

    protected function getStats(): array
    {
        $todayStart = CarbonImmutable::now()->startOfDay();
        $weekStart = CarbonImmutable::now()->startOfWeek();

        return [
            Stat::make('Meetings Today', Meeting::query()->where('meeting_at', '>=', $todayStart)->count()),
            Stat::make('Meetings This Week', Meeting::query()->where('meeting_at', '>=', $weekStart)->count()),
            Stat::make('Total Meetings', Meeting::query()->count()),
            Stat::make('Won Leads', Company::query()->where('status', 'won')->count()),
        ];
    }
}
