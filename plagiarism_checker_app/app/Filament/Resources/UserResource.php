<?php

namespace App\Filament\Resources;

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

    protected static ?string $navigationGroup = 'Access Management';

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    protected static ?string $navigationLabel = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->required()
                    ->options(Role::all()->pluck('name', 'name')->toArray())
                    ->default(User::STUDENT_ROLE),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->confirmed(),
                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->required()
                    ->label('Confirm Password'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable()
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options(fn() => Role::all()->pluck('name', 'id')->toArray())
                    ->relationship('roles', 'name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Action::make('Assign Role')
                    ->label('Assign Role')
                    ->icon('heroicon-o-key')
                    ->color(Color::Green)
                    ->action(function (User $user, array $data): void {
                        try {
                            $roleNames = Role::whereIn('id', $data['roles'])->get()->pluck('name')->toArray();
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
                        Forms\Components\CheckboxList::make('role_id')
                            ->label('Roles')
                            ->options(Role::all()->pluck('name', 'id')->toArray())
                            ->columns(2)
                            ->reactive()
                            ->default(fn(User $user) => [$user->roles->first()->id])
                            ->afterStateUpdated(fn($state, callable $set) => !empty($state) && $set('roles', [array_values($state)[1]]))
                    ])
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

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
