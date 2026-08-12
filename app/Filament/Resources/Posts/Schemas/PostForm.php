<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Автор')
                    ->options(
                        User::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                TextInput::make('title')
                    ->label('Название')
                    ->required()
                    ->maxLength(255),

                Textarea::make('text')
                    ->label('Текст публикации')
                    ->required()
                    ->rows(10)
                    ->columnSpanFull(),
            ]);
    }
}