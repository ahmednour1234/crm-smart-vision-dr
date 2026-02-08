<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Carbon\CarbonImmutable;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TopAgentsThisWeek extends BaseWidget
{
    protected static ?string $heading = 'Top Agents (Meetings This Week)';

    protected function getTableQuery(): Builder
    {
        $weekStart = CarbonImmutable::now()->startOfWeek();

        return User::query()
            ->where('role', 'sales')
            ->select([
                'users.*',
                DB::raw('count(meetings.id) as meetings_count'),
            ])
            ->leftJoin('meetings', function ($join) use ($weekStart): void {
                $join
                    ->on('users.id', '=', 'meetings.user_id')
                    ->where('meetings.meeting_at', '>=', $weekStart);
            })
            ->groupBy('users.id')
            ->orderByDesc('meetings_count');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label('Agent'),
            Tables\Columns\TextColumn::make('meetings_count')->label('Meetings')->sortable(),
        ];
    }

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 10;
    }
}
