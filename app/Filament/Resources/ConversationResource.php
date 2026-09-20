<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConversationResource\Pages;
use App\Models\Conversation;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ConversationResource extends Resource
{
    protected static ?string $model = Conversation::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Customer Chats';
    protected static ?string $pluralModelLabel = 'Customer Conversations';
    protected static ?int $navigationSort = 5;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('messages_count')
                    ->counts('messages')
                    ->label('Total Messages')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Activity')
                    ->dateTime('M d, Y - h:i A')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Open Chat')
                    ->icon('heroicon-m-eye')
                    ->color('primary'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Customer Info')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')->label('Customer Name'),
                        Infolists\Components\TextEntry::make('user.email')->label('Email'),
                    ])->columns(2),

                Infolists\Components\Section::make('Chat History')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('messages')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('sender.name')
                                    ->label('Sender')
                                    ->weight('bold')
                                    ->color(fn ($record) => $record->sender?->role === 'admin' ? 'danger' : 'success'),

                                Infolists\Components\TextEntry::make('message')
                                    ->label('Message'),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Sent At')
                                    ->dateTime('Y-m-d h:i A')
                                    ->color('gray'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConversations::route('/'),
            'view'  => Pages\ViewConversation::route('/{record}'),
        ];
    }
}