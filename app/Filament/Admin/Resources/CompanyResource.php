<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CompanyResource\Pages;
use App\Models\Company;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Companies';

    protected static function currentUser(): ?User
    {
        /** @var User|null $user */
        $user = Filament::auth()->user();

        // مهم: حمّل role + permissions لو Role::hasPermission بيحتاجهم
        if ($user) {
            $user->loadMissing('role.permissions');
        }

        return $user;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    public static function canViewAny(): bool
    {
        $user = static::currentUser();
        return $user && ($user->hasPermission('company.view.any') || $user->hasPermission('company.view'));
    }

    public static function canCreate(): bool
    {
        $user = static::currentUser();
        return $user && $user->hasPermission('company.create');
    }

    public static function canEdit($record): bool
    {
        $user = static::currentUser();
        if (!$user) return true;

        if ($user->hasPermission('company.update.any')) return true;
        if (!$user->hasPermission('company.update')) return false;

        return (int) $record->created_by === (int) $user->id
            || (int) $record->owner_id === (int) $user->id;
    }

    public static function canDelete($record): bool
    {
        $user = static::currentUser();
        if (!$user) return false;

        if ($user->hasPermission('company.delete.any')) return true;
        if (!$user->hasPermission('company.delete')) return false;

        return (int) $record->created_by === (int) $user->id
            || (int) $record->owner_id === (int) $user->id;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['owner', 'event', 'package', 'country', 'createdBy', 'bookedBy']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('company_name')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('status')
                ->options([
                    'new' => 'New',
                    'contacted' => 'Contacted',
                    'meeting' => 'Meeting',
                    'negotiation' => 'Negotiation',
                    'won' => 'Won',
                    'lost' => 'Lost',
                ])
                ->required(),

            Forms\Components\Select::make('event_id')
                ->relationship('event', 'name')
                ->searchable()
                ->nullable(),

            Forms\Components\Select::make('package_id')
                ->relationship('package', 'name')
                ->searchable()
                ->nullable(),

            Forms\Components\Select::make('country_id')
                ->relationship('country', 'name')
                ->searchable()
                ->nullable(),

            Forms\Components\TextInput::make('contact_person')->maxLength(255)->nullable(),
            Forms\Components\TextInput::make('contact_mobile')->maxLength(50)->nullable(),
            Forms\Components\TextInput::make('contact_email')->email()->maxLength(255)->nullable(),

            Forms\Components\DatePicker::make('next_followup_date')->nullable(),

            Forms\Components\Textarea::make('notes')->columnSpanFull()->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('owner.name')->label('Owner')->sortable(),

                Tables\Columns\TextColumn::make('booked')
                    ->label('Booked')
                    ->getStateUsing(function (Company $record) {
                        $uid = Filament::auth()->id();

                        if ((int) $record->created_by === (int) $uid) return 'Your Company';

                        if ($record->booked_by) {
                            if ((int) $record->booked_by === (int) $uid) return 'Booked by You';
                            return $record->bookedBy?->name ?? 'Booked';
                        }

                        return '-';
                    })
                    ->badge()
                    ->color(fn (Company $record) => match (true) {
                        (int) $record->created_by === (int) Filament::auth()->id() => 'success',
                        (int) $record->booked_by === (int) Filament::auth()->id() => 'info',
                        $record->booked_by !== null => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('event.name')->label('Event')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country.name')->label('Country')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('next_followup_date')->date()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'meeting' => 'Meeting',
                        'negotiation' => 'Negotiation',
                        'won' => 'Won',
                        'lost' => 'Lost',
                    ]),
                Tables\Filters\Filter::make('my_companies')
                    ->label('My Companies')
                    ->query(fn (Builder $query) => $query->where('created_by', Filament::auth()->id()))
                    ->toggle(),
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
            'index'  => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit'   => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
