<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static function isAdmin(): bool
    {
        return Filament::auth()->user()?->role?->slug === 'admin';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::isAdmin();
    }

    protected static function getCurrentUser(): ?User
    {
        return Filament::auth()->user();
    }

    public static function canViewAny(): bool
    {
        return static::isAdmin();
    }

    public static function canCreate(): bool
    {
        return static::isAdmin();
    }

    public static function canEdit($record): bool
    {
        return static::isAdmin();
    }

    public static function canDelete($record): bool
    {
        return static::isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn (string $operation): bool => $operation === 'create')
                ->dehydrated(fn (?string $state, string $operation): bool => $operation === 'create' ? filled($state) : filled($state))
                ->helperText('Required on create. Leave empty on edit to keep current password.'),

            Forms\Components\Select::make('role_id')
                ->relationship('role', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->label('Role'),

            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('companies_count')
                    ->label('Companies')
                    ->counts('companies')
                    ->badge()
                    ->color('warning')
                    ->sortable(),
                Tables\Columns\TextColumn::make('meetings_count')
                    ->label('Meetings')
                    ->counts('meetings')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('role.name')
                    ->label('Roles')
                    ->badge()
                    ->color(fn ($record) => match ($record->role?->slug) {
                        'admin'   => 'danger',
                        'manager' => 'warning',
                        default   => 'success',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with('role');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
