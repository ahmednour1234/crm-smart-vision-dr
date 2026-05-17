<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopUsersWidget extends BaseWidget
{
    protected static ?string $heading = 'Top Users';

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = Filament::auth()->user();

        return $user?->role?->slug === 'admin';
    }

    protected function getTableQuery(): Builder
    {
        return User::query()
            ->with('role')
            ->withCount(['bookings', 'companies'])
            ->orderByDesc('companies_count');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('User Name')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('email')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('bookings_count')
                ->label('Bookings')
                ->badge()
                ->color('success')
                ->sortable(),

            Tables\Columns\TextColumn::make('companies_count')
                ->label('Companies')
                ->badge()
                ->color('warning')
                ->sortable(),

            Tables\Columns\TextColumn::make('role.name')
                ->label('Roles')
                ->badge()
                ->color(fn ($record) => match ($record->role?->slug) {
                    'admin'   => 'danger',
                    'manager' => 'warning',
                    default   => 'success',
                }),
        ];
    }

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 10;
    }
}
