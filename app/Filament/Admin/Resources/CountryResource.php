<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CountryResource\Pages;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasPermission('country.view.any') ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasPermission('country.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->hasPermission('country.update') ?? false;
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->hasPermission('country.delete') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('iso2')->required()->maxLength(2)->minLength(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('iso2')->sortable(),
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
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
