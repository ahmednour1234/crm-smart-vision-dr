<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PackageResource\Pages;
use App\Models\Package;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    protected static function getCurrentUser(): ?User
    {
        return Filament::auth()->user();
    }

    public static function canViewAny(): bool
    {
        $user = static::getCurrentUser();
        return $user && ($user->hasPermission('package.view.any') || $user->hasPermission('package.view'));
    }

    public static function canCreate(): bool
    {
        $user = static::getCurrentUser();
        return $user && $user->hasPermission('package.create');
    }

    public static function canEdit($record): bool
    {
        $user = static::getCurrentUser();
        return $user && $user->hasPermission('package.update');
    }

    public static function canDelete($record): bool
    {
        $user = static::getCurrentUser();
        return $user && $user->hasPermission('package.delete');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('price')->numeric()->required()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('price')->money('USD'),
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
