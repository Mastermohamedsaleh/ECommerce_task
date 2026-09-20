<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log; 

class FcmService
{  

protected $messaging;

    public function __construct()
    {
        // 1. مسار ملف الـ Credentials
        $credentialsPath = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase/service-account.json'));

        // 2. التحقق من وجود الملف
        if (file_exists($credentialsPath)) {
            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $this->messaging = $factory->createMessaging();
        } else {
            $this->messaging = null;
        }
    }
    public function sendNotification(string $token, string $title, string $body, array $data = [])
    {
        // في حالة عدم وجود ملف الجيسون في البيئة المحلية، سيتم محاكاة الإشعار في الـ Log
        if (!$this->messaging) {
            Log::info("FCM Notification (Simulation): Title: {$title} | Body: {$body} | Token: {$token}");
            return false;
        }

        try {
            $notification = Notification::create($title, $body);

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData($data);

            $this->messaging->send($message);

            return true;
        } catch (\Throwable $e) {
            Log::error("FCM Send Error: " . $e->getMessage());
            return false;
        }
    }

}