<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupResource\Pages;
use App\Models\Group;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Группы';
    protected static ?string $modelLabel = 'Группа';
    protected static ?string $pluralModelLabel = 'Группы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Название группы')
                    ->required(),

                Forms\Components\Select::make('specialty_id')
                    ->label('Специальность')
                    ->relationship('specialty', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                // Работаем с полем 'course' как с выбором номера курса из списка
                Forms\Components\Select::make('course')
                    ->label('Курс')
                    ->options([
                        1 => '1 курс',
                        2 => '2 курс',
                        3 => '3 курс',
                        4 => '4 курс',
                        5 => '5 курс',
                        6 => '6 курс',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Группа')->searchable(),
                Tables\Columns\TextColumn::make('specialty.name')->label('Специальность'),
                
                // Отображаем значение из колонки 'course' напрямую
                Tables\Columns\TextColumn::make('course')
                    ->label('Курс')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }
}