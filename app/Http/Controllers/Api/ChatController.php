<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Services\FcmService;



class ChatController extends Controller
{
    use ApiResponse; 

    public function getMessages(Request $request)
    {
        $user = $request->user();


        $conversation = Conversation::firstOrCreate([
            'user_id' => $user->id,
        ]);

        $messages = Message::where('conversation_id', $conversation->id)
            ->with('sender:id,name')
            ->oldest()
            ->get();

        return $this->successResponse([
            'conversation_id' => $conversation->id,
            'messages'        => $messages,
        ], 'تم جلب الرسائل بنجاح');

    }


 public function sendMessage(Request $request, FcmService $fcmService)
{
    // 1. استخراج المستخدم الحالي الأول قبل أي شرط
    $user = $request->user();

    // 2. التحقق والتأكد من المحادثة حسب الدور
    if ($user->role === 'admin') {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message'         => 'required|string|max:2000',
        ]);

        $conversationId = $request->conversation_id;
    } else {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $conversation = Conversation::firstOrCreate([
            'user_id' => $user->id,
        ]);

        $conversationId = $conversation->id;
    }

    // 3. حفظ الرسالة
    $message = Message::create([
        'conversation_id' => $conversationId,
        'sender_id'       => $user->id,
        'message'         => $request->message,
    ]);

    $message->load('sender:id,name,role');

    // 4. البث عبر الـ Event
    broadcast(new MessageSent($message))->toOthers();

    // 5. إرسال FCM Notification لو اللي بيبعت أدمن
    if ($user->role === 'admin') {
        $conversation = Conversation::with('user')->find($conversationId);
        $customer = $conversation->user;

        if ($customer && $customer->fcm_token) {
            $fcmService->sendNotification(
                $customer->fcm_token,
                'رسالة جديدة من الدعم الفني 💬',
                $request->message,
                ['conversation_id' => (string) $conversationId]
            );
        }
    }

    return $this->successResponse($message, 'تم إرسال الرسالة بنجاح', 201);
}
}
