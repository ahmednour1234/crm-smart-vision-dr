<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Companies';

    /**
     * ✅ Turn on/off dd() from one place
     */
    private const DEBUG_PERMISSIONS = true;

    /**
     * ✅ If your schema differs, edit only these:
     */
    private const USERS_TABLE = 'users';
    private const ROLES_TABLE = 'roles';
    private const PERMISSIONS_TABLE = 'permissions';

    // pivot table name (change if yours is role_permissions)
    private const ROLE_PERMISSION_PIVOT = 'role_permission';

    // permission column holding slugs like "company.view.any" (change to key/name if needed)
    private const PERMISSION_SLUG_COLUMN = 'slug';

    public static function shouldRegisterNavigation(): bool
    {
        // ✅ Don't always return true; register only if allowed
        return static::canViewAny();
    }

    /**
     * ✅ Use correct guard for the current Filament panel
     * (Fixes issue where Filament::auth()->id() can be null)
     */
    protected static function authGuard(): string
    {
        return Filament::getCurrentPanel()?->getAuthGuard() ?? 'web';
    }

    protected static function authId(): ?int
    {
        return auth(static::authGuard())->id();
    }

    /**
     * Manual DB permission check:
     * users.role_id -> role_permission -> permissions.slug
     */
    protected static function userHasPermissionDb(?int $userId, string $permissionSlug): bool
    {
        $permissionSlug = trim($permissionSlug);
    
        if (!$userId || $permissionSlug === '') {
            return false;
        }
    
        $userRow = DB::table('users')
            ->select('id', 'is_active', 'role_id')
            ->where('id', $userId)
            ->first();
    
        if (!$userRow) return false;
        if (empty($userRow->is_active)) return false;
        if (empty($userRow->role_id)) return false;
    
        $pivot = 'role_permission'; // ✅ عدلها لو اسمها مختلف
    
        // ✅ نخليها مرنة على أعمدة permissions
        $permQuery = DB::table($pivot)
            ->join('permissions', 'permissions.id', '=', "{$pivot}.permission_id")
            ->where("{$pivot}.role_id", $userRow->role_id)
            ->where(function ($q) use ($permissionSlug) {
                // جرّب slug
                $q->orWhere('permissions.slug', $permissionSlug);
                // جرّب key
                $q->orWhere('permissions.key', $permissionSlug);
                // جرّب name
                $q->orWhere('permissions.name', $permissionSlug);
            });
    
        return $permQuery->exists();
    }
    

    protected static function userHasAnyPermissionDb(?int $userId, array $slugs): bool
    {
        foreach ($slugs as $slug) {
            if (static::userHasPermissionDb($userId, $slug)) return true;
        }
        return false;
    }

    /**
     * ✅ Debug helper (dd)
     * This will stop execution and show you exactly where it fails.
     */
    protected static function ddPermissions(string $context, array $checkSlugs): void
    {
        if (!self::DEBUG_PERMISSIONS) return;

        $panel = Filament::getCurrentPanel();
        $guard = static::authGuard();

        $filamentId = Filament::auth()->id();
        $webId = auth('web')->id();
        $panelId = $panel?->getId();

        $uid = static::authId();

        $userRow = $uid
            ? DB::table(self::USERS_TABLE)->select('id', 'is_active', 'role_id')->where('id', $uid)->first()
            : null;

        $roleSlug = null;
        if ($userRow?->role_id) {
            $roleSlug = DB::table(self::ROLES_TABLE)
                ->where('id', $userRow->role_id)
                ->value('slug');
        }

        $results = [];
        foreach ($checkSlugs as $slug) {
            $results[$slug] = static::userHasPermissionDb($uid, $slug);
        }

        dd([
            'context' => $context,
            'panel_id' => $panelId,
            'panel_guard' => $guard,

            'filament_auth_id' => $filamentId,
            'web_auth_id' => $webId,
            'used_auth_id' => $uid,

            'user_row' => $userRow,
            'role_slug' => $roleSlug,

            'pivot_table' => self::ROLE_PERMISSION_PIVOT,
            'permission_column' => self::PERMISSION_SLUG_COLUMN,

            'checks' => $results,
        ]);
    }

    public static function canViewAny(): bool
    {
        static::ddPermissions('CompanyResource::canViewAny', [
            'company.view.any',
            'company.view',
        ]);

        $uid = static::authId();

        return static::userHasAnyPermissionDb($uid, [
            'company.view.any',
            'company.view',
        ]);
    }

    public static function canCreate(): bool
    {
        static::ddPermissions('CompanyResource::canCreate', [
            'company.create',
        ]);

        return static::userHasPermissionDb(static::authId(), 'company.create');
    }

    public static function canEdit($record): bool
    {
        static::ddPermissions('CompanyResource::canEdit', [
            'company.update.any',
            'company.update',
        ]);

        $uid = static::authId();
        if (!$uid) return false;

        if (static::userHasPermissionDb($uid, 'company.update.any')) return true;
        if (! static::userHasPermissionDb($uid, 'company.update')) return false;

        return (int) $record->created_by === (int) $uid || (int) $record->owner_id === (int) $uid;
    }

    public static function canDelete($record): bool
    {
        static::ddPermissions('CompanyResource::canDelete', [
            'company.delete.any',
            'company.delete',
        ]);

        $uid = static::authId();
        if (!$uid) return false;

        if (static::userHasPermissionDb($uid, 'company.delete.any')) return true;
        if (! static::userHasPermissionDb($uid, 'company.delete')) return false;

        return (int) $record->created_by === (int) $uid || (int) $record->owner_id === (int) $uid;
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
