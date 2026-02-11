<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PermissionResource\Pages;
use App\Models\Permission;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationLabel = 'Permissions';

    public static function canViewAny(): bool
    {
        return Filament::auth()->user()?->hasPermission('permission.view.any') ?? false;
    }

    public static function canCreate(): bool
    {
        return Filament::auth()->user()?->hasPermission('permission.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Filament::auth()->user()?->hasPermission('permission.update') ?? false;
    }

    public static function canDelete($record): bool
    {
        return Filament::auth()->user()?->hasPermission('permission.delete') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->helperText('Unique identifier for the permission (e.g., company.view, permission.create)'),

            Forms\Components\Select::make('resource')
                ->options(function () {
                    return Permission::distinct()
                        ->whereNotNull('resource')
                        ->pluck('resource', 'resource')
                        ->toArray();
                })
                ->searchable()
                ->nullable()
                ->helperText('Group permissions by resource/module'),

            Forms\Components\Textarea::make('description')
                ->rows(3)
                ->columnSpanFull()
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('resource')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('roles_count')
                    ->counts('roles')
                    ->label('Roles')
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('resource')
                    ->options(function () {
                        return Permission::distinct()
                            ->whereNotNull('resource')
                            ->pluck('resource', 'resource')
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
