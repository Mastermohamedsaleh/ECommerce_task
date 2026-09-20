<?php

namespace App\Filament\Resources\ConversationResource\Pages;

use App\Filament\Resources\ConversationResource;
use App\Models\Message;
use App\Services\FcmService;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewConversation extends ViewRecord
{
    protected static string $resource = ConversationResource::class;

    protected static ?string $title = 'Chat Conversation Details';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reply')
                ->label('Reply to Customer')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->modalHeading('Send Reply to Customer')
                ->modalSubmitActionLabel('Send Message')
                ->form([
                    Forms\Components\Textarea::make('message')
                        ->label('Write your reply here')
                        ->placeholder('Type your message...')
                        ->required()
                        ->rows(4),
                ])
                ->action(function (array $data, FcmService $fcmService): void {
                    $conversation = $this->getRecord();
                    $admin = auth()->user();

                    Message::create([
                        'conversation_id' => $conversation->id,
                        'sender_id'       => $admin->id,
                        'message'         => $data['message'],
                    ]);

                    $conversation->touch();

                    $customer = $conversation->user;
                    if ($customer && $customer->fcm_token) {
                        $fcmService->sendNotification(
                            $customer->fcm_token,
                            'رسالة جديدة من الدعم الفني 💬',
                            $data['message'],
                            ['conversation_id' => (string) $conversation->id]
                        );
                    }

                    Notification::make()
                        ->title('Reply Sent Successfully')
                        ->body('The customer has been notified via FCM.')
                        ->success()
                        ->send();

                    $this->refreshFormData();
                }),
        ];
    }
}