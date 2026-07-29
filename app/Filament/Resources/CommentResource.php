<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Комментарии';

    // Поля формы для создания/редактирования
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('post_id')
                    ->relationship('post', 'title')
                    ->label('Новость')
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Пользователь (если авторизован)'),

                Forms\Components\TextInput::make('user_name')
                    ->label('Имя гостя'),

                Forms\Components\Textarea::make('body')
                    ->label('Текст комментария')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_approved')
                    ->label('Одобрен')
                    ->default(true),
            ]);
    }

    // Таблица со списком комментариев
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post.title')
                    ->label('Новость')
                    ->limit(30)
                    ->sortable(),

                Tables\Columns\TextColumn::make('user_name')
                    ->label('Автор')
                    ->getStateUsing(fn (Comment $record) => $record->user ? $record->user->name : $record->user_name)
                    ->searchable(),

                Tables\Columns\TextColumn::make('body')
                    ->label('Комментарий')
                    ->limit(40),

                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Одобрен')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime()
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}