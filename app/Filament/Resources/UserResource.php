<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Group;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Пользователи';

    protected static ?string $modelLabel = 'Пользователь';

    protected static ?string $pluralModelLabel = 'Пользователи';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Имя'),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email'),

                Forms\Components\Toggle::make('is_admin')
                    ->label('Администратор'),

                // Выбор Группы (динамически подтягивает факультет и специальность)
                Forms\Components\Select::make('group_id')
                    ->relationship('group', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->label('Группа'),

                // Авто-информация о Факультете (только чтение)
                Forms\Components\Placeholder::make('faculty_info')
                    ->label('Факультет')
                    ->content(function ($get) {
                        $groupId = $get('group_id');
                        if (! $groupId) {
                            return '—';
                        }

                        $group = Group::with('specialty.faculty')->find($groupId);

                        return $group?->specialty?->faculty?->name ?? '—';
                    }),

                // Авто-информация о Специальности (только чтение)
                Forms\Components\Placeholder::make('specialty_info')
                    ->label('Специальность')
                    ->content(function ($get) {
                        $groupId = $get('group_id');
                        if (! $groupId) {
                            return '—';
                        }

                        $group = Group::with('specialty')->find($groupId);

                        return $group?->specialty?->name ?? '—';
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Имя')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                // Вывод наименования связанной группы
                Tables\Columns\TextColumn::make('group.name')
                    ->label('Группа')
                    ->searchable()
                    ->sortable(),

                // Вывод названия Факультета через метод-аксессор getFacultyAttribute
                Tables\Columns\TextColumn::make('faculty.name')
                    ->label('Факультет'),

                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Админ')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата регистрации')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}