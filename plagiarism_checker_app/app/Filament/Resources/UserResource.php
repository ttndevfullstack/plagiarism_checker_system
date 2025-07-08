<?php

namespace App\Filament\Resources;

use App\Filament\Components\Actions\BaseActionGroup;
use App\Filament\Components\Filters\BaseFilterGroup;
use App\Filament\Components\Tables\RoleSelector;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationGroup = 'Access Management';

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    protected static ?string $navigationLabel = 'User Management';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('dob')
                    ->label('Date of Birth')
                    ->required(false),
                Forms\Components\TextInput::make('phone')
                    ->label('Phone')
                    ->maxLength(20)
                    ->tel()
                    ->required(false),
                Forms\Components\TextInput::make('address')
                    ->label('Address')
                    ->maxLength(255)
                    ->required(false),
                Forms\Components\FileUpload::make('avatar')
                    ->label('Avatar')
                    ->image()
                    ->directory('avatars')
                    ->required(false),
                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->required()
                    ->options(Role::all()->pluck('name', 'name')->toArray()),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->confirmed()
                    ->required(static fn($record) => is_null($record)),
                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->label('Confirm Password')
                    ->required(static fn($record) => is_null($record)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dob')
                    ->label('Date of Birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Address')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->circular()
                    ->height(40)
                    ->width(40),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable()
                    ->default(__('Not registered'))
                    ->formatStateUsing(static fn($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                RoleSelector::show(),
                ...BaseFilterGroup::show(),
            ])
            ->actions([
                ...BaseActionGroup::show(),

                Action::make('Assign Role')
                    ->label('Assign Role')
                    ->icon('heroicon-o-key')
                    ->color(Color::Green)
                    ->action(static function (User $user, array $data): void {
                        try {
                            $roleNames = Role::whereIn('id', $data['role_ids'])->get()->pluck('name')->toArray();
                            $user->syncRoles($roleNames);

                            Notification::make()
                                ->title('Saved successfully')
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Something went wrong')
                                ->success(false)
                                ->color('danger')
                                ->iconColor('danger')
                                ->send();
                        }
                    })
                    ->form([
                        Forms\Components\CheckboxList::make('role_ids')
                            ->label('Roles')
                            ->options(Role::all()->pluck('name', 'id')->toArray())
                            ->columns(2)
                            ->reactive()
                            ->default(static fn(User $user) => $user->roles->isNotEmpty() ? [$user->roles->first()->id] : [])
                            ->afterStateUpdated(static fn($state, callable $set) => !empty($state) && $set('role_ids', [array_pop($state)])),
                    ])
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * @return array<string, \Filament\Resources\Pages\Page> 
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
