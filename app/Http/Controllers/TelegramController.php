<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlatform;
use App\Models\QrCode;
use App\Models\TelegramSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\FileUpload\InputFile;

class TelegramController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Telegram webhook hit:', $request->all());

        $update = Telegram::commandsHandler(true);
        $message = $update->getMessage();

        if (!$message) {
            return response()->json(['ok' => true]);
        }

        $chatId = $message->getChat()->getId();
        $text = trim((string) $message->getText());
        $messageId = $message->getMessageId();
        $photos = $message->getPhoto();

        $linkedUser = User::where('telegram_chat_id', $chatId)->first();
        $session = TelegramSession::where('telegram_chat_id', $chatId)->first();

        // ---------- /start ----------
        if ($text === '/start') {
            if ($linkedUser) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "You're already linked as {$linkedUser->name}. Forward me a QR image to save it, or send /get to view your saved QR codes.",
                ]);
            } else {
                TelegramSession::updateOrCreate(
                    ['telegram_chat_id' => $chatId],
                    ['step' => 'awaiting_email', 'temp_email' => null]
                );

                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Welcome to QRWallet Bot! Let's link your account.\n\nPlease enter your QRWallet email:",
                ]);
            }
            return response()->json(['ok' => true]);
        }

        // ---------- Linking flow: awaiting email ----------
        if ($session && $session->step === 'awaiting_email') {
            $user = User::where('email', $text)->first();

            if (!$user) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "No QRWallet account found with that email. Please try again:",
                ]);
                return response()->json(['ok' => true]);
            }

            $session->update(['temp_email' => $text, 'step' => 'awaiting_password']);

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Enter your QRWallet password:",
            ]);
            return response()->json(['ok' => true]);
        }

        // ---------- Linking flow: awaiting password ----------
        if ($session && $session->step === 'awaiting_password') {
            Telegram::deleteMessage(['chat_id' => $chatId, 'message_id' => $messageId]);

            $user = User::where('email', $session->temp_email)->first();

            if (!$user || !Hash::check($text, $user->password)) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Invalid credentials. Try again.\n\nPlease enter your QRWallet email:",
                ]);
                $session->update(['step' => 'awaiting_email', 'temp_email' => null]);
                return response()->json(['ok' => true]);
            }

            $user->telegram_chat_id = $chatId;
            $user->save();
            $session->delete();

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Account linked successfully! Hi, {$user->name}.\n\nForward me a QR image to save it, or send /get to view your saved QR codes.",
            ]);
            return response()->json(['ok' => true]);
        }

        // ---------- Must be linked from here on ----------
        if (!$linkedUser) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "You're not linked yet. Send /start to link your QRWallet account.",
            ]);
            return response()->json(['ok' => true]);
        }

        // ---------- Retrieve QR: /get command ----------
        if ($text === '/get') {
            $qrCodes = QrCode::where('user_id', $linkedUser->id)
                ->with('paymentPlatform')
                ->orderBy('id')
                ->get();

            if ($qrCodes->isEmpty()) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "No saved QRs found. Forward me a QR image to save one.",
                ]);
                return response()->json(['ok' => true]);
            }

            $list = $qrCodes->map(
                fn($qr, $i) =>
                ($i + 1) . ". {$qr->label} ({$qr->paymentPlatform->platform_name})"
            )->implode("\n");

            TelegramSession::updateOrCreate(
                ['telegram_chat_id' => $chatId],
                ['step' => 'awaiting_retrieve_selection']
            );

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Select a QR to retrieve by number:\n\n{$list}",
            ]);
            return response()->json(['ok' => true]);
        }

        // ---------- Retrieve QR: awaiting selection ----------
        if ($session && $session->step === 'awaiting_retrieve_selection') {
            $qrCodes = QrCode::where('user_id', $linkedUser->id)
                ->with('paymentPlatform')
                ->orderBy('id')
                ->get();

            $index = (int) $text - 1;

            if (!is_numeric($text) || $index < 0 || $index >= count($qrCodes)) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Invalid choice. Try again.",
                ]);
                return response()->json(['ok' => true]);
            }

            $qr = $qrCodes[$index];
            $session->delete();

            $safeLabel = preg_replace('/[^A-Za-z0-9_-]/', '_', $qr->label);
            $safePlatform = preg_replace('/[^A-Za-z0-9_-]/', '_', $qr->paymentPlatform->platform_name);
            $filename = "QRW_{$safeLabel}_{$safePlatform}.png";

            $fullPath = Storage::disk('public')->path($qr->qr_image);

            Telegram::sendDocument([
                'chat_id' => $chatId,
                'document' => InputFile::create($fullPath, $filename),
            ]);

            return response()->json(['ok' => true]);
        }

        // ---------- Logout: unlink Telegram account ----------
        if ($text === '/logout') {
            $linkedUser->telegram_chat_id = null;
            $linkedUser->save();

            // Clean up any stray session for this chat
            TelegramSession::where('telegram_chat_id', $chatId)->delete();

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Account unlinked successfully. Send /start to link again.",
            ]);
            return response()->json(['ok' => true]);
        }

        // ---------- Save QR: user forwarded/sent a photo ----------
// ---------- Save QR: user forwarded/sent a photo ----------
        if ($photos && count($photos) > 0) {
            // Telegram sends multiple sizes; take the largest (last item)
            $largestPhoto = collect($photos)->last();
            $fileId = $largestPhoto->getFileId();

            $file = Telegram::getFile(['file_id' => $fileId]);
            $filePath = $file->getFilePath(); // e.g. photos/file_123.jpg

            $botToken = config('telegram.bots.mybot.token');
            $fileUrl = "https://api.telegram.org/file/bot{$botToken}/{$filePath}";

            $imageContents = Http::get($fileUrl)->body();

            $filename = 'qr_codes/' . uniqid('tg_') . '.jpg';
            Storage::disk('public')->put($filename, $imageContents);

            TelegramSession::updateOrCreate(
                ['telegram_chat_id' => $chatId],
                [
                    'step' => 'awaiting_label',
                    'temp_image_path' => $filename,
                    'temp_email' => null,
                ]
            );

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Got the QR image! Please enter a label for it:",
            ]);
            return response()->json(['ok' => true]);
        }

        // ---------- Save QR: awaiting label ----------
        if ($session && $session->step === 'awaiting_label') {
            $session->update(['temp_label' => $text, 'step' => 'awaiting_platform']);

            $platforms = PaymentPlatform::orderBy('id')->get();
            $list = $platforms->map(fn($p, $i) => ($i + 1) . ". {$p->platform_name}")->implode("\n");

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Select payment platform by number:\n\n{$list}",
            ]);
            return response()->json(['ok' => true]);
        }

        // ---------- Save QR: awaiting platform ----------
        if ($session && $session->step === 'awaiting_platform') {
            $platforms = PaymentPlatform::orderBy('id')->get();
            $index = (int) $text - 1;

            if (!is_numeric($text) || $index < 0 || $index >= count($platforms)) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Invalid selection. Try again.",
                ]);
                return response()->json(['ok' => true]);
            }

            $platform = $platforms[$index];

            QrCode::create([
                'user_id' => $linkedUser->id,
                'platform_id' => $platform->id,
                'label' => $session->temp_label,
                'qr_image' => $session->temp_image_path,
            ]);

            $session->delete();

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "QR Saved Successfully!",
            ]);
            return response()->json(['ok' => true]);
        }

        // ---------- Fallback ----------
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "Forward me a QR image to save it, or send /get to view your saved QR codes.",
        ]);
        return response()->json(['ok' => true]);
    }
}