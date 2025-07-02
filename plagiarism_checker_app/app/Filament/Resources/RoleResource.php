<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationGroup = 'Access Management';

    protected static ?string $navigationIcon = 'heroicon-c-shield-check';

    protected static ?string $navigationLabel = 'Role Management';

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Role Name')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Description')
                    ->maxLength(500)
                    ->helperText('Provide a brief description of the role.'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy('resource');

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Role Name')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(static fn ($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('Admin Roles')
                    ->query(static fn (Builder $query) => $query->where('name', 'admin')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color(Color::Blue),
                Tables\Actions\DeleteAction::make(),

                Action::make('Assign Permissions')
                    ->label('Assign Permissions')
                    ->icon('heroicon-o-key')
                    ->color(Color::Green)
                    ->action(static function (Role $role, array $data): void {
                        $role->syncPermissions($data['permissions'] ?? []);
                    })
                    ->form(
                        array_merge(
                            [
                                Forms\Components\Group::make()
                                    ->schema(
                                        [
                                            Forms\Components\Checkbox::make('select_all_permissions')
                                                ->label(__('All Permissions'))
                                                ->reactive()
                                                ->afterStateUpdated(static function ($state, callable $set) use ($permissions, $groupedPermissions): void {
                                                    $set('select_one', $state ? $permissions->pluck('id')->toArray() : []);

                                                    foreach ($groupedPermissions as $group) {
                                                        $set('select_all_' . $group->first()->resource, $state);
                                                    }
                                                })
                                                ->inline(),
                                        ]
                                    )
                                    ->extraAttributes(['style' => 'padding-bottom: 24px; border-bottom: 1px #ccc solid']),
                            ],

                            collect($groupedPermissions)->map(static function ($group) {
                                return [
                                    Forms\Components\Group::make()
                                        ->schema(
                                            [
                                                Forms\Components\Checkbox::make('select_all_' . $group->first()->resource)
                                                    ->label(__(__(ucfirst($group->first()->resource . ' Module'))))
                                                    ->reactive()
                                                    ->afterStateUpdated(static function ($state, callable $set) use ($group): void {
                                                        $set('select_one', $state ? $group->pluck('id') : []);
                                                    })
                                                    ->inline(),

                                                Forms\Components\CheckboxList::make('select_one')
                                                    ->label('')
                                                    ->options(
                                                        $group->pluck('name', 'id')
                                                            ->mapWithKeys(static function ($name, $id) {
                                                                return [$id => __('messages.module.permission.' . str_replace(':', '.', $name))];
                                                            })
                                                            ->toArray()
                                                    )
                                                    ->default(static fn (User $user) => $user->getAllPermissions()->pluck('id')->toArray())
                                                    ->columns(2),
                                            ]
                                        )
                                        ->extraAttributes(['style' => 'padding-bottom: 24px; border-bottom: 1px #ccc solid']),
                                ];
                            })->flatten()->toArray()
                        )
                    )
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return array<string, \Filament\Resources\Pages\Page> 
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
